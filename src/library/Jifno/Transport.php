<?php
namespace Jifno;

interface Transport
{
    public function send(\Jifno\Message $message);
}