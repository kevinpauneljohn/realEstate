<?php

namespace App\Services;

class FilesService
{
    public function icons($type): string
    {
        if($type === 'jpg')
        {
            $icon = 'jpg.png';
        }
        elseif($type === 'png')
        {
            $icon = 'png.png';
        }
        elseif($type === 'gif')
        {
            $icon = 'gif.png';
        }
        elseif($type === 'pdf')
        {
            $icon = 'pdf.png';
        }
        elseif($type === 'doc')
        {
            $icon = 'doc.png';
        }
        elseif($type === 'docx')
        {
            $icon = 'office.png';
        }
        elseif($type === 'xls')
        {
            $icon = 'xls.png';
        }
        elseif($type === 'xlsx')
        {
            $icon = 'xlsx.png';
        }
        elseif($type === 'ppt')
        {
            $icon = 'ppt.png';
        }
        elseif($type === 'pptx')
        {
            $icon = 'pptx-file.png';
        }
        else{
            $icon = '';
        }
        return $icon;
    }
}
