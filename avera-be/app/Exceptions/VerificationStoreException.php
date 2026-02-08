<?php

namespace App\Exceptions;

use Throwable;

class VerificationStoreException extends BusinessException
{
    protected int $statusCode = 422;

    public function __construct(string $status)
    {
        switch ($status) {
            case 'verified':
                return parent::__construct("Store sudah diverifikasi!");
                break;
            case 'suspended':
                return parent::__construct("Store ditangguhkan, silakan menghubungi admin!");
                break;
            case 'non-active':
                return parent::__construct("Store tidak aktif!");
                break;
            case 'product':
                return parent::__construct("Produk tidak memenuhi standard minimum");
                break;
            case 'description':
                return parent::__construct("Deskripsi Store belum lengkap");
                break;
            case 'logo':
                return parent::__construct("Logo Store belum ada");
                break;
            default:
                return parent::__construct("Verifikasi sudah pernah disubmit harap menunggu!");
                break;
        }
    }
}
