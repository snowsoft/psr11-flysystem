<?php
declare(strict_types=1);

namespace WShafer\PSR11FlySystem\Adaptor;

use WShafer\PSR11FlySystem\MapperAbstract;

class AdaptorMapper extends MapperAbstract
{
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getFactoryClassName(string $type)
    {
        if (class_exists($type)) {
            return $type;
        }

        switch ($type) {
            case 'dropbox':
                return DropBoxAdapterFactory::class;
            case 's3':
                return S3AdapterFactory::class;
            case 'azure':
                return AzureAdapterFactory::class;
            case 'zip':
                return ZipArchiveAdaptorFactory::class;
            case 'memory':
                return MemoryAdaptorFactory::class;
            case 'ftp':
                return FtpAdaptorFactory::class;
            case 'sftp':
                return SftpAdaptorFactory::class;
            case 'local':
                return LocalAdaptorFactory::class;
            case 'null':
                return NullAdaptorFactory::class;
        }

        return null;
    }
}
