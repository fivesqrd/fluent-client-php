<?php
namespace Jifno\Storage;

require_once 'Jifno/Message.php';

class Db
{
    /**
     * @var \PDO
     */
    protected $_adapter;
    
    public static $path;
    
    protected static $_instance;
    
    /**
     * @return Jifno\Storage\Db
     */
    public static function getInstance($path = null)
    {
        if (self::$_instance) {
            return self::$_instance;
        }
        
        $path = ($path) ? $path : self::$path;
        if (!is_dir($path)) {
            throw new \Exception('Failed trying to create temporary Jifno message store in: '. $path);
        }
        $file = $path. '/Jifno-Queue.sqlite3';
        $exists = file_exists($file);
        
        $db = new \PDO('sqlite:' . $file);
        $db->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        
        if (!$exists) {
            self::createSchema($db);            
        }
        
        return new self($db);
    }
    
    public static function createSchema(\PDO $db)
    {
        $db->query('CREATE TABLE messages (id integer primary key autoincrement, profile varchar(255), sender varchar(255), recipient varchar(255), subject varchar(255), content blob, status varchar(255), created_at datetime, transmitted_at datetime, reference varchar(255), error varchar(255))');
        $db->query('CREATE TABLE attachments (id integer primary key autoincrement, message_id int(11), type varchar(255), name varchar(255), content blob)');
    }
    
    public function __construct(\PDO $adapter)
    {
        $this->_adapter = $adapter;
    }
    
    public function isLocked($mypid)
    {
        $lockfile = self::$path . '/Jifno-Queue.lock';
        if (file_exists($lockfile)) {
            $pid = (int) file_get_contents($lockfile);
        }
        if (!isset($pid) || posix_getsid($pid) === false) {
            file_put_contents($lockfile, $mypid); // create lockfile
        } else {
            return true;
        }
    }
    
    public function persist(\Jifno\Message $message)
    {
        $properties = $message->toArray();
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
                ':content' => $data['content'], 
                ':name' => $data['name'], 
                ':type' => $data['type']
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