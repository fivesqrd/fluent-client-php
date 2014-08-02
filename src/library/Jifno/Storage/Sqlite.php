<?php
namespace Jifno\Storage;

class Sqlite implements \Jifno\Storage
{
    /**
     * @var \PDO
     */
    protected $_adapter;
    
    protected static $_instance;
    
    protected $_path;
    
    public static $path;
    
    /**
     * @return Jifno\Storage\Db
     */
    public static function getInstance($path = null)
    {
        if (self::$_instance) {
            return self::$_instance;
        }
        
        return new self($path);
    }

    public function __construct($path = null)
    {
        $path = $this->_getPath($path);
        if (!is_dir($path)) {
            throw new \Exception('Failed trying to create temporary Jifno message store in: '. $path);
        }
        $file = $path. '/Jifno-Queue.sqlite3';
        $exists = file_exists($file);
        
        $this->_adapter = new \PDO('sqlite:' . $file);
        $this->_adapter->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        
        if (!$exists) {
            $this->_createSchema($this->_adapter);
            chmod($file, 0777);
        }
    }
    
    protected function _getPath($default)
    {
        if (!empty($default)) {
            $this->_path = $default;
            return $this->_path;
        }
        
        if (!empty(self::$path)) {
            return self::$path;
        }
        
        return sys_get_temp_dir();
    }

    protected function _createSchema(\PDO $db)
    {
        $db->query('CREATE TABLE messages (id integer primary key autoincrement, profile varchar(255), sender varchar(255), recipient varchar(255), subject varchar(255), content blob, status varchar(255), created_at datetime, transmitted_at datetime, reference varchar(255), error varchar(255))');
        $db->query('CREATE TABLE attachments (id integer primary key autoincrement, message_id int(11), type varchar(255), name varchar(255), content blob)');
    }
    
    public function isLocked($mypid)
    {
        $lockfile = $this->_getPath($this->_path) . '/Jifno-Queue.lock';
        if (file_exists($lockfile)) {
            $pid = (int) file_get_contents($lockfile);
        }
        if (!isset($pid) || $pid == 0 || posix_getsid($pid) === false) {
            file_put_contents($lockfile, $mypid); // create lockfile
        } else {
            return true;
        }
    }
    
    public function persist(\Jifno\Email $email)
    {
        $properties = $email->toArray();
        $data = array(
            ':sender'    => json_encode($properties['sender']),
            ':recipient' => json_encode($properties['recipient']),
            ':subject'   => $properties['subject'],
            ':content'   => $properties['content'],
            ':status'    => 'queued',
            ':created_at'=> date("Y-m-d H:i:s"),
            ':profile'   => $properties['profile']
        );
        $stmt = $this->_adapter->prepare('INSERT INTO messages (sender,recipient,subject,content,status,created_at,profile) values (:sender,:recipient,:subject,:content,:status,:created_at,:profile)');
        $stmt->execute($data);
        $id = $this->_adapter->lastInsertId();
        
        foreach ($properties['attachments'] as $data) {
            $stmt = $this->_adapter->prepare('INSERT INTO attachments (message_id,name,type,content) values (:message_id,:name,:type,:content)');
            $stmt->execute(array(
                ':message_id' => $id, 
                ':content'    => base64_decode($data['content']), 
                ':name'       => $data['name'], 
                ':type'       => $data['type']
            ));
        }
        return $id;
    }
    
    public function delete($messageId)
    {
        $this->_adapter->query('DELETE FROM attachments WHERE message_id = ' . $this->_adapter->quote($messageId));
        $this->_adapter->delete('DELETE FROM messages WHERE id = ' .  $this->_adapter->quote($messageId));
    }
    
    public function moveToSent($messageId, $reference)
    {
        $data = array(
            ':id'             => $messageId,
            ':reference'      => $reference,
            ':status'         => 'sent',
            ':time'           => date("Y-m-d H:i:s"),
        );
        $stmt = $this->_adapter
            ->prepare('UPDATE messages SET reference = :reference, status = :status, transmitted_at = :time, error = null WHERE id = :id');
        $stmt->execute($data);
    }

    public function moveToFailed($messageId, $error)
    {
        $data = array(
            ':id'             => $messageId,
            ':status'         => 'failed',
            ':error'          => $error,
            ':time'           => date("Y-m-d H:i:s")
        );
        $stmt = $this->_adapter
            ->prepare('UPDATE messages SET status = :status, transmitted_at = :time, error = :error WHERE id = :id');
        $stmt->execute($data);
    }
    
    public function getQueue()
    {
        $stmt = $this->_adapter
            ->prepare('SELECT * FROM messages WHERE status = :status');
        $stmt->execute(array(':status' => 'queued'));
        return $stmt->fetchAll();
    }
    
    public function getAttachments($messageId)
    {
        $stmt = $this->_adapter
            ->prepare('SELECT * FROM attachments WHERE message_id = :messageId');
        $stmt->execute(array(':messageId' => $messageId));
        return $stmt->fetchAll();
    }
    
    public function purge($days)
    {
        $stmt = $this->_adapter
            ->prepare('DELETE FROM messages WHERE status != :status AND created_at < :date');
        $stmt->execute(array(
            ':status' => 'queued', 
            ':date' => date("Y-m-d H:i:s", time() - $days * 86400)
        ));
    }
}