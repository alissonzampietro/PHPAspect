<?php

namespace Aspecto\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\After;
use Aspecto\Repository\ConnectionFactory;

/**
 * Monitor aspect
 */
class TransactionalAspect implements Aspect
{

    /**
     * Method that will be called before real method
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public **->persist(*))")
     */
    public function beginTransaction(MethodInvocation $invocation)
    {
    	$pdo = ConnectionFactory::getConnection();
    	$pdo->beginTransaction();
    }

    /**
     * Method that will be called before real method
     *
     * @param MethodInvocation $invocation Invocation
     * @After("execution(public **->persist(*))")
     */
    public function commit(MethodInvocation $invocation)
    {
    	$pdo = ConnectionFactory::getConnection();
    	$pdo->commit();
    }


    /**
     * Method that will be called before real method
     *
     * @param MethodInvocation $invocation Invocation
     * @AfterThrowing("execution(public **->persist(*))")
     */
    public function rollback(MethodInvocation $invocation)
    {
    	$pdo = ConnectionFactory::getConnection();
    	$pdo->rollBack();
    }
}