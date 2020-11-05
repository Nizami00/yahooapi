<?php
if (!empty($existRate)) {
    query()
        ->update('currency_rates')
        ->set('name', ':name')
        ->set('rate', ':rate')
        ->setParameters([
            'name' => $name,
            'rate' => $rate
        ])
        ->where('name = :name')
        ->setParameter('name', $name)
        ->execute();
} else {
    query()
        ->insert('currency_rates')
        ->values([
            'name' => ':name',
            'rate' => ':rate'
        ])
        ->setParameter('name', $name)
        ->setParameter('rate', $rate)
        ->execute();
}