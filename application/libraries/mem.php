<?php

/**
 * Classe para abstração de Memcache e Memcached. Interface simples para trabalhar com
 * ambas.
 *
 * @author Felipe Rosa
 */
class mem {

    private $memcache = null;

    public function __construct() {

        if ($this->isEnabled()) {

            if ($this->eOrEd() == 'memcache')
                $this->memcache = new Memcache; // Old one
            else
                $this->memcache = new Memcached(); // PECL extension, good one

            $ci = &get_instance();
            $servers = $ci->config->item("memserver");

            if (count($servers) > 0) {
                foreach ($servers as $server)
                    $this->memcache->addServer($server['host'], $server['port']);
            }
        }
    }

    /**
     * Retorna se deve usar memcache ou memcached
     * @return string
     */
    private function eOrEd(){
        $ci = &get_instance();
        return $ci->config->item('MemcacheOrMemcached');
    }
    
    /**
     * Roda o método flush da memcache e da memcached
     * Limpa todas as keys do servidor.
     */
    public function flush() {
        $this->memcache->flush();
    }

    /**
     * Memcache/Memcached está habilitada no config?
     * 
     * @return boolean
     */
    public function isEnabled() {
        $ci = &get_instance();
        return $ci->config->item("useMemcache");
    }

    /**
     * Volta o valor definido a uma chave. Se não foi definido valor
     * volta null. false.
     * 
     * @param type $key
     * @return string/array/null
     */
    public function userdata($key) {
        return $this->memcache->get($key);
    }

    /**
     * Define/Sobrescreve o valor para uma chave no servidor
     * 
     * Tempo padrão de 180 segundos, 3 minutos a key tem validade.
     * 
     * @param string $key
     * @param string/array $value
     * @param int $seconds
     */
    public function set_userdata($key, $value, $seconds = 180) {

        if ($this->eOrEd() == 'memcache') {
            $this->memcache->set($key, $value, MEMCACHE_COMPRESSED, $seconds);
        } else {
            $this->memcache->set($key,$value, $seconds);
        }
    }

}
