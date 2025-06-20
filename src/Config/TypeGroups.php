<?php

namespace Jletrondo\Upload\Config;

class TypeGroups {
    const IMAGES = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
    const DOCUMENTS = [
        'pdf', 'doc', 'docx', 'csv', 'xls', 'xlsx', 'txt'
    ];
    const MIME_TYPES = [
    'png'   => 'image/png',
    'jpg'   => 'image/jpeg',
    'jpeg'  => 'image/jpeg',
    'gif'   => 'image/gif',
    'bmp'   => 'image/bmp',
    'pdf'   => 'application/pdf',
    'doc'   => 'application/msword',
    'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'csv'   => 'text/csv',
    'xls'   => 'application/vnd.ms-excel',
    'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'txt'   => 'text/plain'
    // add more as needed
];
}