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

        $this->em->persist($entity);
        $this->em->flush();
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
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);

        $this->em->persist($entity);
        $this->em->flush();
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
        $entity = $this->em->getReference($this->entity, $id);
        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }
    }

}
