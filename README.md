[![codecov](https://codecov.io/gh/wshafer/psr11-flysystem/branch/master/graph/badge.svg)](https://codecov.io/gh/wshafer/psr11-flysystem)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wshafer/psr11-flysystem/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wshafer/psr11-flysystem/?branch=master)
[![Build Status](https://travis-ci.org/wshafer/psr11-flysystem.svg?branch=master)](https://travis-ci.org/wshafer/psr11-flysystem)
# PSR-11 FlySystem

[FlySystem](https://flysystem.thephpleague.com/) Factories for PSR-11

#### Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
    - [Adaptors](#adaptors)
        - [Null / Test](#nulltest)
        - [Local](#local)
        - [FTP](#ftp)
        - [SFTP](#sftp)
        - [Memory](#memory)
        - [Zip Archive](#zip-archive)
        - [Azure](#azure)
        - [AWS S3](#aws-s3)
        - [DropBox](#dropbox)
    - [Caches](#caches)
        - [Memory / Test](#memorytest)
        - [PSR-6](#psr-6)
    - [File System](#file-system)
    - [Example](#full-example)
- [Usage](#usage)

# Installation

```bash
composer require wshafer/PSR11FlySystem
```

# Configuration

Fly System uses three types of services that will each need to be configured.

- [Adaptors](#adaptors) : These are the adaptors to to the actual file system.  This could be
an Azure container, S3, Local, Memory, etc.

- [Caches](#caches) : Cache layer to optimize performance.  While this is optional, this package
will use a memory cache by default if none is provide.

- [File System](#file-system) :  This will configure the final FlySystem File System that
you will actually use to do the work.   This uses the previous two configurations to get
you a fully functioning File System to work with.   In addition you can also configure
a [Mount Manager](https://flysystem.thephpleague.com/mount-manager/) to wire up multiple
file systems that need to interact with one another.

### Adaptors
Example configs for supported adaptors

#### Null/Test

```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'null',
                'options' => [], #No options available
            ],
        ],
    ],
];
```
FlySystem Docs: [Null Adaptor](https://flysystem.thephpleague.com/adapter/null-test/)

#### Local

```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'local',
                'options' => [
                    'root' => '/path/to/root', # Path on local filesystem
                    'writeFlags' => LOCK_EX, # PHP flags.  See: file_get_contents for more info
                    'linkBehavior' => League\Flysystem\Adapter\Local::DISALLOW_LINKS, #Link behavior
                    'permissions' => [
                        'file' => [
                            'public' => 0644,
                            'private' => 0600,
                        ],
                        'dir' => [
                            'public' => 0755,
                            'private' => 0700,
                        ]    
                    ]
                ],
            ],
        ],
    ],
];
```

FlySystem Docs: [Local Adaptor](https://flysystem.thephpleague.com/adapter/local/)

#### FTP

```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'ftp',
                'options' => [
                    'host' => 'ftp.example.com',
                    'username' => 'username',
                    'password' => 'password',
                
                    /** optional config settings */
                    'port' => 21,
                    'root' => '/path/to/root',
                    'passive' => true,
                    'ssl' => true,
                    'timeout' => 30,
                ],
            ],
        ],
    ],
];
```

FlySystem Docs: [FTP](https://flysystem.thephpleague.com/adapter/ftp/)

#### SFTP
**Install**
```bash
composer require league/flysystem-sftp
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'ftp',
                'options' => [
                    'host' => 'example.com',
                    'port' => 21,
                    'username' => 'username',
                    'password' => 'password',
                    'privateKey' => 'path/to/or/contents/of/privatekey',
                    'root' => '/path/to/root',
                    'timeout' => 10,
                ],
            ],
        ],
    ],
];
```

FlySystem Docs: [SFTP](https://flysystem.thephpleague.com/adapter/sftp/)

#### Memory

**Install**
```bash
composer require league/flysystem-memory
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'memory',
                'options' => [],
            ],
        ],
    ],
];
```

FlySystem Docs: [Memory](https://flysystem.thephpleague.com/adapter/memory/)

#### Zip Archive

**Install**
```bash
composer require league/flysystem-ziparchive
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'zip',
                'options' => [
                    'path' => '/some/path/to/file.zip'    
                ],
            ],
        ],
    ],
];
```

FlySystem Docs: [Zip Archive](https://flysystem.thephpleague.com/adapter/zip-archive/)

#### Azure

**Install**
```bash
composer require league/flysystem-azure
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'azure',
                'options' => [
                    'accountName' => 'account-name',
                    'apiKey' => 'api-key',
                    'container' => 'container-name',
                    'prefix' => 'prefix_',  # Optional
                ],
            ],
        ],
    ],
];
```
FlySystem Docs: [Azure Adaptor](https://flysystem.thephpleague.com/adapter/azure/)

#### AWS S3
_Note: AWS V2 is not supported in this package_

**Install**
```bash
composer require league/flysystem-aws-s3-v3
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 's3',
                'options' => [
                    'key' => 'aws-key',
                    'secret'  => 'aws-secret',
                    'region'  => 'us-east-1',
                    'bucket'  => 'bucket-name',
                    'prefix'  => 'some/prefix', #optional
                    'version' => 'latest' # Default: 'latest'
                ],
            ],
        ],
    ],
];
```
FlySystem Docs: [Aws S3 Adapter - SDK V3](https://flysystem.thephpleague.com/adapter/aws-s3-v3/)

#### DropBox

**Install**
```bash
composer require spatie/flysystem-dropbox
```

**Config**
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            'myAdaptorName' => [
                'type' => 'dropbox',
                'options' => [
                    'token'   => 'my-token',
                    'prefix'  => 'prefix', #optional
                ],
            ],
        ],
    ],
];
```
FlySystem Docs: [DropBox](https://flysystem.thephpleague.com/adapter/dropbox/)

## Caches
Example configs for supported caches

#### Memory/Test

```php
<?php

return [
    'flysystem' => [
        'caches' => [
            'local' => [
                'type' => 'memory',
                'options' => [], #No options available
            ],
        ],
    ],
];
```
FlySystem Docs: [Caching](https://flysystem.thephpleague.com/caching/)

#### PSR-6

```php
<?php

return [
    'flysystem' => [
        'caches' => [
            'local' => [
                'type' => 'psr6',
                'options' => [
                    'service' => 'my_psr6_service_from_container', # Service to be used from the container
                    'key' => 'my_key_', # Cache Key
                    'ttl' => 3000 # Expires
                ],
            ],
        ],
    ],
];
```
FlySystem Docs: Unknown 

### File System
```php
<?php

return [
    'flysystem' => [
        'fileSystems' => [
            # Array Keys are the file systems identifiers
            'local' => [
                'adaptor' => 'adaptor_one', # Adaptor name from adaptor configuration
                'cache' => 'PSR6\Cache\Service', # Cache name from adaptor configuration
                'plugins' => [] # User defined plugins to be injected into the file system
            ],
            
            # Mount Manager Config
            'manager' => [
                'adaptor' => 'manager',
                'fileSystems' => [
                    'local' => [
                        'adaptor' => 'adaptor_one', # Adaptor name from adaptor configuration
                        'cache' => 'cache_one', # PSR-6 pre-configured service
                        'plugins' => [] # User defined plugins to be injected into the file system
                    ],
                    
                    'anotherFileSystem' => [
                        'adaptor' => 'adaptor_two', # Adaptor name from adaptor configuration
                        'cache' => 'cache_two', # PSR-6 pre-configured service
                        'plugins' => [] # User defined plugins to be injected into the file system
                    ],
                ],
            ],
        ],
    ],
];
```

### Full Example
```php
<?php

return [
    'flysystem' => [
        'adaptors' => [
            # Array Keys are the names used for the adaptor
            'adaptor_one' => [
                'type' => 'local', #A daptor name or pre-configured service from the container
                'options' => [], # Adaptor specific options.  See adaptors below
            ],
            
            'adaptor_two' => [
                'type' => 'null', #A daptor name or pre-configured service from the container
                'options' => [], # Adaptor specific options.  See adaptors below
            ],
        ],
        
        'caches' => [
            # Array Keys are the names used for the cache
            'cache_one' => [
                'type' => 'psr6',
                # Cache specific options.  See caches below
                'options' => [
                    'service' => 'my_psr6_service_from_container',
                    'key' => 'my_key_',
                    'ttl' => 3000
                ], 
            ],
            
            'cache_two' => [
                'type' => 'psr6',
                # Cache specific options.  See caches below
                'options' => [
                    'service' => 'my_psr6_service_from_container',
                    'key' => 'my_key_',
                    'ttl' => 3000
                ], 
            ],
            
        ],
        
        'fileSystems' => [
            # Array Keys are the file systems identifiers
            'local' => [
                'adaptor' => 'adaptor_one', # Adaptor name from adaptor configuration
                'cache' => 'PSR6\Cache\Service', # Cache name from adaptor configuration
                'plugins' => [] # User defined plugins to be injected into the file system
            ],
            
            # Mount Manager Config
            'manager' => [
                'adaptor' => 'manager',
                'fileSystems' => [
                    'local' => [
                        'adaptor' => 'adaptor_one', # Adaptor name from adaptor configuration
                        'cache' => 'cache_one', # PSR-6 pre-configured service
                        'plugins' => [] # User defined plugins to be injected into the file system
                    ],
                    
                    'anotherFileSystem' => [
                        'adaptor' => 'adaptor_two', # Adaptor name from adaptor configuration
                        'cache' => 'cache_two', # PSR-6 pre-configured service
                        'plugins' => [] # User defined plugins to be injected into the file system
                    ],
                ],
            ],
        ],
    ],
];

```

# Usage

```php
<?php

# Get the file system manager from the container
$fileSystemManager = $container->get(\WShafer\PSR11FlySystem\Service\FileSystemManager::class);

# Get the FlySystem FileSystem Or Manager
$fileSystem = $fileSystemManager->get('local');
$manager = $fileSystemManager->get('manager');
```

Additional info can be found in the [documentation](https://flysystem.thephpleague.com/)