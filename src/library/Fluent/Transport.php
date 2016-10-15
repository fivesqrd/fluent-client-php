<?php
namespace Fluent;

interface Transport
{
    public function send(\Fluent\Message\Create $message);
}
