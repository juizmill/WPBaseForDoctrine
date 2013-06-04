<?php
/**
 * AbstractService
 *
 * Classe abstrata de Serviços, insert, update e delete.
 *
 * @access abstract
 * @package WPBase\Controller
 */

namespace WPBase\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

abstract class AbstractService
{

    protected $em;
    protected $entity;

    /**
     * __construct
     *
     * Conexão com o banco de dados.
     *
     * @access public
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * insert
     *
     * Realiza um cadastro no banco de dado.
     *
     * @access public
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        $entity = new $this->entity($data);

        $this->getEm()->persist($entity);
        $this->getEm()->flush();

        return $entity;
    }

    /**
     * update
     *
     * Realiza uma alteração no banco de dados.
     *
     * @access public
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {
        if (! isset($data['id']))
            throw new \InvalidArgumentException('A key ID é obrigatório dentro do array');

        $entity = $this->getEm()->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);

        $this->getEm()->persist($entity);
        $this->getEm()->flush();

        return $entity;
    }

    /**
     * delete
     *
     * Remove um registro do banco de dados.
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if (! is_numeric($id))
            throw new \InvalidArgumentException('Campo ID deve ser nunérico');

        $entity = $this->getEm()->getReference($this->entity, $id);

        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return $id;
    }

    /**
     * getEm
     *
     * Obtem conexao com o banco de dados
     *
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

}
