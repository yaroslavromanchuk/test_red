<?php
class bug extends wsActiveRecord
{
    public $_error;


    protected $_table = 'bug_tracker';
    protected $_orderby = array('id' => 'ASC');

        
        public static function add_exception($e){
             $bug  = wsActiveRecord::useStatic('bug')->findFirst(['code'=> $e->getCode(), 'line' => (int)$e->getLine(), 'type' => self::error('EXCEPTION'), 'message' => $e->getMessage()]);
            if($bug->id){
                $bug->count = ($bug->count+1);
            }else{
            $bug = new bug();
            $bug->class = get_class($e);
            $bug->message = $e->getMessage();
            $bug->code = $e->getCode();
            $bug->file = (string)$e->getFile();
            $bug->line = (int)$e->getLine();
            $bug->trace = serialize($e->getTrace());
            $bug->type = 'EXCEPTION';
            $bug->count = 1;
            $bug->get = serialize($_GET);
            $bug->post = serialize($_POST);
            $bug->session = serialize($_SESSION);
            $bug->files = serialize($_FILES);
            $bug->server = serialize($_SERVER);
            $bug->request = serialize($_REQUEST);
            }
            $bug->fix = 0;
            $bug->save(); 
        }
        public static function add_error($errno, $errstr, $errfile, $errline){
       
            $bug  = wsActiveRecord::useStatic('bug')->findFirst(['code'=> $errno, 'line' => $errline, 'type' => self::error($errno), 'message' => $errstr]);
            if(isset($bug->id)){
                $bug->count = ($bug->count+1);
            }else{
            $bug = new bug();
            $bug->class = get_class();
            $bug->message = $errstr;
            $bug->code = $errno;
            $bug->file = (string)$errfile;
            $bug->line = (int)$errline;
           // $bug->trace = serialize($e->getTrace());
            $bug->type = self::error($errno);
            $bug->count = 1;
            $bug->get = serialize($_GET);
            $bug->post = serialize($_POST);
            $bug->session = serialize($_SESSION);
            $bug->files = serialize($_FILES);
            $bug->server = serialize($_SERVER);
            $bug->request = serialize($_REQUEST);
            }
            $bug->fix = 0;
            $bug->save(); 
        }
        
        static private function error($errno){
             switch ($errno) {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_COMPILE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_COMPILE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
        case 'EXCEPTION': return  'EXCEPTION'; // исключение
       
    default: return $errno;
    }
        }
        
        
}
