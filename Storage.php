<?php
namespace Jifno;

interface Storage
{
    public function persist(\Jifno\Message $message);
}