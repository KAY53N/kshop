<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * PDO数据库驱动类
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Think
 * @subpackage  Db
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class DbPdo extends Db{

    protected $PDOStatement = null;
    private   $table = '';

    /**
     +----------------------------------------------------------
     * 架构函数 读取数据库配置信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $config 数据库配置数组
     +----------------------------------------------------------
     */
    public function __construct($config=''){
        if ( !class_exists('PDO') ) {
            throw_exception(L('_NOT_SUPPERT_').':PDO');
        }
        if(!empty($config)) {
            $this->config   =   $config;
            if(empty($this->config['params'])) {
                $this->config['params'] =   array();
            }
        }

    }

    /**
     +----------------------------------------------------------
     * 连接数据库方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function connect($config='',$linkNum=0) {
        if ( !isset($this->linkID[$linkNum]) ) {
            if(empty($config))  $config =   $this->config;
            if($this->pconnect) {
                $config['params'][PDO::ATTR_PERSISTENT] = true;
            }
            //$config['params'][PDO::ATTR_CASE] = C("DB_CASE_LOWER")?PDO::CASE_LOWER:PDO::CASE_UPPER;
            try{
                $this->linkID[$linkNum] = new PDO( $config['dsn'], $config['username'], $config['password'],$config['params']);
            }catch (PDOException $e) {
                throw_exception($e->getMessage());
            }
            // 因为PDO的连接切换可能导致数据库类型不同，因此重新获取下当前的数据库类型
            $this->dbType = $this->_getDsnType($config['dsn']);
            if(in_array($this->dbType,array('MSSQL','ORACLE','IBASE','OCI'))) {
                // 由于PDO对于以上的数据库支持不够完美，所以屏蔽了 如果仍然希望使用PDO 可以注释下面一行代码
                throw_exception('由于目前PDO暂时不能完美支持'.$this->dbType.' 请使用官方的'.$this->dbType.'驱动');
            }
            $this->linkID[$linkNum]->exec('SET NAMES '.C('DB_CHARSET'));
            // 标记连接成功
            $this->connected    =   true;
            // 注销数据库连接配置信息
            if(1 != C('DB_DEPLOY_TYPE')) unset($this->config);
        }
        return $this->linkID[$linkNum];
    }

    /**
     +----------------------------------------------------------
     * 释放查询结果
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function free() {
        $this->PDOStatement = null;
    }

    /**
     +----------------------------------------------------------
     * 执行查询 返回数据集
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  sql指令
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function query($str) {
        $this->initConnect(false);
        if ( !$this->_linkID ) return false;
        $this->queryStr = $str;
        //释放前次的查询结果
        if ( !empty($this->PDOStatement) ) $this->free();
        N('db_query',1);
        // 记录开始执行时间
        G('queryStartTime');
        $this->PDOStatement = $this->_linkID->prepare($str);
        if(false === $this->PDOStatement)
            throw_exception($this->error());
        $result =   $this->PDOStatement->execute();
        $this->debug();
        if ( false === $result ) {
            $this->error();
            return false;
        } else {
            return $this->getAll();
        }
    }

    /**
     +----------------------------------------------------------
     * 执行语句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  sql指令
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function execute($str) {
        $this->initConnect(true);
        if ( !$this->_linkID ) return false;
        $this->queryStr = $str;
        $flag = false;
        if($this->dbType == 'OCI')
        {
            if(preg_match("/^\s*(INSERT\s+INTO)\s+(\w+)\s+/i", $this->queryStr, $match)) {
                $this->table = C("DB_SEQUENCE_PREFIX").str_ireplace(C("DB_PREFIX"), "", $match[2]);
                $flag = (boolean)$this->query("SELECT * FROM user_sequences WHERE sequence_name='" . strtoupper($this->table) . "'");
            }
        }//modify by wyfeng at 2009.08.28
        //释放前次的查询结果
        if ( !empty($this->PDOStatement) ) $this->free();
        N('db_write',1);
        // 记录开始执行时间
        G('queryStartTime');
        $this->PDOStatement	=	$this->_linkID->prepare($str);
        if(false === $this->PDOStatement) {
            throw_exception($this->error());
        }
        $result	=	$this->PDOStatement->execute();
        $this->debug();
        if ( false === $result) {
            $this->error();
            return false;
        } else {
            $this->numRows = $result;
            if($flag || preg_match("/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $str)) {
                $this->lastInsID = $this->getLastInsertId();
            }
            return $this->numRows;
        }
    }

    /**
     +----------------------------------------------------------
     * 启动事务
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function startTrans() {
        $this->initConnect(true);
        if ( !$this->_linkID ) return false;
        //数据rollback 支持
        if ($this->transTimes == 0) {
            $this->_linkID->beginTransaction();
        }
        $this->transTimes++;
        return ;
    }

    /**
     +----------------------------------------------------------
     * 用于非自动提交状态下面的查询提交
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function commit()
    {
        if ($this->transTimes > 0) {
            $result = $this->_linkID->commit();
            $this->transTimes = 0;
            if(!$result){
                throw_exception($this->error());
            }
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 事务回滚
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function rollback()
    {
        if ($this->transTimes > 0) {
            $result = $this->_linkID->rollback();
            $this->transTimes = 0;
            if(!$result){
                throw_exception($this->error());
            }
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 获得所有的查询数据
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    private function getAll() {
        //返回数据集
        $result =   $this->PDOStatement->fetchAll(constant('PDO::FETCH_ASSOC'));
        $this->numRows = count( $result );
        return $result;
    }

    /**
     +----------------------------------------------------------
     * 取得数据表的字段信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function getFields($tableName) {
        $this->initConnect(true);
        if(C('DB_DESCRIBE_TABLE_SQL')) {
            // 定义特殊的字段查询SQL
            $sql   = str_replace('%table%',$tableName,C('DB_DESCRIBE_TABLE_SQL'));
        }else{
            switch($this->dbType) {
                case 'MSSQL':
                    $sql   = "SELECT   column_name as 'Name',   data_type as 'Type',   column_default as 'Default',   is_nullable as 'Null'
        FROM    information_schema.tables AS t
        JOIN    information_schema.columns AS c
        ON  t.table_catalog = c.table_catalog
        AND t.table_schema  = c.table_schema
        AND t.table_name    = c.table_name
        WHERE   t.table_name = '$tableName'";
                    break;
                case 'SQLITE':
                    $sql   = 'PRAGMA table_info ('.$tableName.') ';
                    break;
                case 'ORACLE':
                case 'OCI':
                    $sql   = "SELECT a.column_name \"Name\",data_type \"Type\",decode(nullable,'Y',0,1) notnull,data_default \"Default\",decode(a.column_name,b.column_name,1,0) \"pk\" "
                      ."FROM user_tab_columns a,(SELECT column_name FROM user_constraints c,user_cons_columns col "
                      ."WHERE c.constraint_name=col.constraint_name AND c.constraint_type='P' and c.table_name='".strtoupper($tableName)
                      ."') b where table_name='".strtoupper($tableName)."' and a.column_name=b.column_name(+)";
                    break;
                case 'PGSQL':
                    $sql   = 'select fields_name as "Name",fields_type as "Type",fields_not_null as "Null",fields_key_name as "Key",fields_default as "Default",fields_default as "Extra" from table_msg('.$tableName.');';
                    break;
                case 'IBASE':
                    break;
                case 'MYSQL':
                default:
                    $sql   = 'DESCRIBE '.$tableName;//备注: 驱动类不只针对mysql，不能加``
            }
        }
        $result = $this->query($sql);
        $info   =   array();
        if($result) {
            foreach ($result as $key => $val) {
                $val['Name'] = isset($val['name'])?$val['name']:$val['Name'];
                $name= strtolower(isset($val['Field'])?$val['Field']:$val['Name']);
                $info[$name] = array(
                    'name'    => $name ,
                    'type'    => $val['Type'],
                    'notnull' => (bool)(((isset($val['Null'])) && ($val['Null'] === '')) || ((isset($val['notnull'])) && ($val['notnull'] === ''))), // not null is empty, null is yes
                    'default' => isset($val['Default'])? $val['Default'] :(isset($val['dflt_value'])?$val['dflt_value']:""),
                    'primary' => isset($val['Key'])?strtolower($val['Key']) == 'pri':(isset($val['pk'])?$val['pk']:false),
                    'autoinc' => isset($val['Extra'])?strtolower($val['Extra']) == 'auto_increment':(isset($val['Key'])?$val['Key']:false),
                );
            }
        }
        return $info;
    }

    /**
     +----------------------------------------------------------
     * 取得数据库的表信息
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function getTables($dbName='') {
        if(C('DB_FETCH_TABLES_SQL')) {
            // 定义特殊的表查询SQL
            $sql   = str_replace('%db%',$dnName,C('DB_FETCH_TABLES_SQL'));
        }else{
            switch($this->dbType) {
            case 'ORACLE':
            case 'OCI':
                $sql   = 'SELECT table_name FROM user_tables';
                break;
            case 'MSSQL':
                $sql   = "SELECT TABLE_NAME	FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'";
                break;
            case 'PGSQL':
                $sql   = "select tablename as Tables_in_test from pg_tables where  schemaname ='public'";
                break;
            case 'IBASE':
                // 暂时不支持
                throw_exception(L('_NOT_SUPPORT_DB_').':IBASE');
                break;
            case 'SQLITE':
                $sql   = "SELECT name FROM sqlite_master WHERE type='table' "
                         . "UNION ALL SELECT name FROM sqlite_temp_master "
                         . "WHERE type='table' ORDER BY name";
                 break;
            case 'MYSQL':
            default:
                if(!empty($dbName)) {
                   $sql    = 'SHOW TABLES FROM '.$dbName;
                }else{
                   $sql    = 'SHOW TABLES ';
                }
            }
        }
        $result = $this->query($sql);
        $info   =   array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    /**
     +----------------------------------------------------------
     * limit分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $lmit
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseLimit($limit) {
        $limitStr    = '';
        if(!empty($limit)) {
            switch($this->dbType){
                case 'PGSQL':
                case 'SQLITE':
                    $limit  =   explode(',',$limit);
                    if(count($limit)>1) {
                        $limitStr .= ' LIMIT '.$limit[1].' OFFSET '.$limit[0].' ';
                    }else{
                        $limitStr .= ' LIMIT '.$limit[0].' ';
                    }
                    break;
                case 'MSSQL':
                    break;
                case 'IBASE':
                    // 暂时不支持
                    break;
                case 'ORACLE':
                case 'OCI':
                    break;
                case 'MYSQL':
                default:
                    $limitStr .= ' LIMIT '.$limit.' ';
            }
        }
        return $limitStr;
    }

    /**
     +----------------------------------------------------------
     * 关闭数据库
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function close() {
        $this->_linkID = null;
    }

    /**
     +----------------------------------------------------------
     * 数据库错误信息
     * 并显示当前的SQL语句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function error() {
        if($this->PDOStatement) {
            $error = $this->PDOStatement->errorInfo();
            $this->error = $error[2];
        }else{
            $this->error = '';
        }
        if($this->debug && '' != $this->queryStr){
            $this->error .= "\n [ SQL语句 ] : ".$this->queryStr;
        }
        return $this->error;
    }

    /**
     +----------------------------------------------------------
     * SQL指令安全过滤
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  SQL指令
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function escape_string($str) {
         switch($this->dbType)
         {
            case 'PGSQL':
            case 'MSSQL':
            case 'IBASE':
            case 'MYSQL':
                return addslashes($str);
            case 'SQLITE':
            case 'ORACLE':
            case 'OCI':
                return str_ireplace("'", "''", $str);
        }
    }

    /**
     +----------------------------------------------------------
     * 获取最后插入id
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    public function getLastInsertId()
    {
         switch($this->dbType)
         {
            case 'PGSQL':
            case 'SQLITE':
            case 'MSSQL':
            case 'IBASE':
            case 'MYSQL':
                return $this->_linkID->lastInsertId();
            case 'ORACLE':
            case 'OCI':
                $sequenceName = $this->table;
                $vo = $this->query("SELECT {$sequenceName}.currval currval FROM dual");
                return $vo?$vo[0]["currval"]:0;
        }
    }

   /**
     +----------------------------------------------------------
     * 析构方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __destruct()
    {
        // 关闭连接
        $this->close();
    }
}//类定义结束
?>