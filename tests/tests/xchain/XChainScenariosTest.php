<?php

use Illuminate\Support\Facades\Log;
use \PHPUnit_Framework_Assert as PHPUnit;

class XChainScenariosTest extends TestCase {

    protected $useRealSQLiteDatabase = true;


    public function testSingleScenario() {
        $scenario_number = getenv('SCENARIO');

        if ($scenario_number !== false) {
            $this->drainQueue();
            $this->runScenario($scenario_number);
        }

        $this->drainQueue();
    }

    public function testAllScenarios() {
        $starting_scenario_number = getenv('SCENARIO');
        if (!$starting_scenario_number) { $starting_scenario_number = 1; }

        // do all state tests in directory
        $scenario_number_count = count(glob(base_path().'/tests/fixtures/scenarios/*.yml'));
        PHPUnit::assertGreaterThan(0, $scenario_number_count);
        for ($i=$starting_scenario_number; $i <= $scenario_number_count; $i++) { 
            Log::debug("\n".str_repeat('-', 60)."\nBEGIN SCENARIO: $i\n".str_repeat('-', 60));
            // clear the db
            if ($i > 1) { $this->resetForScenario(); }

            try {
                $this->runScenario($i);
            } catch (Exception $e) {
                echo "\nFailed while running scenario $i\n";
                throw $e;
            }
        }
    }

    protected function runScenario($scenario_number) {
        // $this->init();

        $filename = "scenario".sprintf('%02d', $scenario_number).".yml";
        $scenario_runner = $this->scenarioRunner();
        $scenario_data = $scenario_runner->loadScenario($filename);
        $scenario_runner->runScenario($scenario_data);
        $scenario_runner->validateScenario($scenario_data);
    }

    protected function scenarioRunner() {
        if (!isset($this->scenario_runner)) {
            $this->scenario_runner = $this->app->make('\ScenarioRunner')->init($this);
        }
        return $this->scenario_runner;
    }

    protected function resetForScenario() {
        // reset the DB
        $this->teardownDb();
        $this->setUpDb();

        // reset the scenario runner
        $this->scenario_runner = null;

        // drain the notification queue
        $this->drainQueue();
    }

    protected function drainQueue() {
        // make sure scenario runner exists
        $this->scenarioRunner();

        // drain the queue
        $q = $this->app->make('Illuminate\Queue\QueueManager');
        // while ($q->connection('notifications_out')->pop()) {}
        $q->connection('notifications_out')->drain();
    }

}
