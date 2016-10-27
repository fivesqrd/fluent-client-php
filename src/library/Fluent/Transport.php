<?php
namespace Fluent;

interface Transport
{
    const LOCAL  = 'local';
    const REMOTE = 'remote';

    public function send(\Fluent\Message\Create $message);
}
