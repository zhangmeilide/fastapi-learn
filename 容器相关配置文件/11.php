<?php

public function processFilesAndSave($responseData, $evidenceTsaId, $serialNo)
{
    $tempPath = storage_path('temp/' . time() . '_process/'); // 临时处理路径
    $finalZipPath = 'evidence/' . date('Ymd') . '/download/'; // 最终压缩包路径
    $pdfSavePath = 'evidence/' . date('Ymd') . '/pdf/'; // PDF 文件存储路径
    $imageSavePath = 'evidence/' . date('Ymd') . '/tsaimage/'; // 图片存储路径

    // 创建处理路径
    if (!is_dir($tempPath)) {
        mkdir($tempPath, 0777, true);
    }

    $collectedFiles = []; // 用于收集压缩包路径

    // 下载 applyFileStorePath
    if (!empty($responseData['applyFileStorePath'])) {
        $applyZipPath = $tempPath . 'apply.zip';
        file_put_contents($applyZipPath, file_get_contents($responseData['applyFileStorePath']));
        $collectedFiles[] = $applyZipPath;

        // 解压 applyFileStorePath 中的图片文件并处理
        $applyExtractPath = $tempPath . uniqid('apply_') . '/';
        $this->extractZip($applyZipPath, $applyExtractPath);
        $images = $this->findFiles($applyExtractPath, ['jpg', 'jpeg', 'png']);
        foreach ($images as $image) {
            $imageFileName = $imageSavePath . time() . get_rand_str(4) . '.jpg';
            Storage::put($imageFileName, file_get_contents($image));

            // 保存缩略图
            $compressedImage = ysimg::make($imageFileName)->resize(
                1080,
                ceil(getimagesize($imageFileName)[1] * 1080 / getimagesize($imageFileName)[0])
            );
            $thumbnailName = $imageSavePath . 'thumb_' . basename($imageFileName);
            $compressedImage->save($thumbnailName, filesize($imageFileName) <= 1024 ? null : 70);
            Storage::delete($imageFileName);

            // 保存到 TsaImg 表
            TsaImg::create([
                'evidence_tsa_id' => $evidenceTsaId,
                'thumbnail_imgpath' => $thumbnailName
            ]);
        }
    }

    // 下载 pdfStorePath
    if (!empty($responseData['pdfStorePath'])) {
        $pdfZipPath = $tempPath . 'pdf.zip';
        file_put_contents($pdfZipPath, file_get_contents($responseData['pdfStorePath']));
        $collectedFiles[] = $pdfZipPath;

        // 解压 pdfStorePath 提取 PDF 并保存到 file_path
        $pdfExtractPath = $tempPath . uniqid('pdf_') . '/';
        $this->extractZip($pdfZipPath, $pdfExtractPath);
        $pdfFiles = $this->findFiles($pdfExtractPath, ['pdf']);
        foreach ($pdfFiles as $pdfFile) {
            $pdfFileName = $pdfSavePath . basename($pdfFile);
            Storage::put($pdfFileName, file_get_contents($pdfFile));
        }
    }

    // 打包两个压缩包文件
    $finalZipFile = $finalZipPath . time() . '_final.zip';
    $this->createZip($collectedFiles, $finalZipFile);

    // 更新 EvidenceTsa 表的 download 字段
    EvidenceTsa::where('seria_no', $serialNo)->update(['download' => $finalZipFile]);

    // 清理临时目录
    $this->deleteDirectory($tempPath);
}

private function extractZip($zipPath, $extractTo)
{
    $zip = new \ZipArchive();
    if ($zip->open($zipPath) === true) {
        $zip->extractTo($extractTo);
        $zip->close();
    } else {
        throw new \Exception("无法解压缩包: {$zipPath}");
    }
}

private function findFiles($directory, $extensions = [])
{
    $files = [];
    $scannedFiles = scandir($directory);
    foreach ($scannedFiles as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = rtrim($directory, '/') . '/' . $file;
        if (is_dir($filePath)) {
            $files = array_merge($files, $this->findFiles($filePath, $extensions));
        } elseif (is_file($filePath) && preg_match('/\.(' . implode('|', $extensions) . ')$/i', $filePath)) {
            $files[] = $filePath;
        }
    }
    return $files;
}

private function createZip($files, $destination)
{
    $zip = new \ZipArchive();
    if ($zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
    } else {
        throw new \Exception("无法创建压缩包: {$destination}");
    }
}

private function deleteDirectory($directory)
{
    if (!is_dir($directory)) {
        return;
    }
    $items = scandir($directory);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . '/' . $item;
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($directory);
}
