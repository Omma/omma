<?php
// @author Adrian Woeltche
$manager = $this->getContainer()->get('h4cc_alice_fixtures.manager');

$set = $manager->createFixtureSet();
$set->addFile(__DIR__ . '/dev.yml', 'yaml');

return $set;
