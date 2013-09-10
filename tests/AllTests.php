<?php
//    session_start();
//      session_cache_expire(30);
/*
 * Run all the RMH Homebase unit tests
 */
// require_once(dirname(__FILE__).'/simpletest/autorun.php');
class AllTests extends GroupTest {
          function AllTests() {
				$this->addTestFile(dirname(__FILE__).'/testPerson.php');
       			$this->addTestFile(dirname(__FILE__).'/testShift.php');
      			$this->addTestFile(dirname(__FILE__).'/testSCL.php');
      		 	$this->addTestFile(dirname(__FILE__).'/testRMHdate.php');
       			$this->addTestFile(dirname(__FILE__).'/testWeek.php');
       			$this->addTestFile(dirname(__FILE__).'/testMonth.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbSCL.php');
       		//	$this->addTestFile(dirname(__FILE__).'/testdbPersons.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbSchedules.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbShifts.php');
      	 		$this->addTestFile(dirname(__FILE__).'/testdbDates.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbWeeks.php');
       			$this->addTestFile(dirname(__FILE__).'/testDataExport.php');
      			$this->addTestFile(dirname(__FILE__).'/testApplicantScreening.php');
    		    $this->addTestFile(dirname(__FILE__).'/testMasterScheduleEntry.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbMasterSchedule.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbMonths.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbDataExport.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbApplicantScreenings.php'); 
        		echo ("\nAll tests complete\n");
          }
 }
?>