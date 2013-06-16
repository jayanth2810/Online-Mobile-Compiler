<?php
error_reporting(0);

$user = 'Jayanth';
$pass = 'jayanth2810';
$code = '';
$input = '';
$run = true;
$private = false;

$subStatus = array(
    0 => 'Success',
    1 => 'Compiled',
    3 => 'Running',
    11 => 'Compilation Error',
    12 => 'Runtime Error',
    13 => 'Timelimit exceeded',
    15 => 'Success',
    17 => 'memory limit exceeded',
    19 => 'illegal system call',
    20 => 'internal error'
);

$error = array(
    'status' => 'error',
    'output' => 'Something went wrong :('
);

//echo json_encode( array( 'hi', 1 ) ); exit;
//print_r( $_POST ); exit;

//if ( isset( $_POST['process'] ) && $_POST['process'] == 1 ) {
    $lang = isset( $_POST['lang'] ) ? intval( $_POST['lang'] ) : 1;
    $input = trim( $_POST['input'] );
    $code = trim( $_POST['source'] );

    $client = new SoapClient( "http://ideone.com/api/1/service.wsdl" );

    //create new submission
    $result = $client->createSubmission( $user, $pass, $code, $lang, $input, $run, $private );

    //if submission is OK, get the status
    if ( $result['error'] == 'OK' ) {
        $status = $client->getSubmissionStatus( $user, $pass, $result['link'] );
        if ( $status['error'] == 'OK' ) {

            //check if the status is 0, otherwise getSubmissionStatus again
            while ( $status['status'] != 0 ) {
                sleep( 3 ); //sleep 3 seconds
                $status = $client->getSubmissionStatus( $user, $pass, $result['link'] );
            }

            //finally get the submission results
            $details = $client->getSubmissionDetails( $user, $pass, $result['link'], true, true, true, true, true );
            if ( $details['error'] == 'OK' ) {
                //print_r( $details );
                if ( $details['status'] < 0 ) {
                    $status = 'waiting for compilation';
                } else {
                    $status = $subStatus[$details['status']];
                }

                /*$data = array(
                    'status' => 'success',
                    'meta' => "Status: $status | Memory: {$details['memory']} | Returned value: {$details['status']} | Time: {$details['time']}s",
                    'output' => htmlspecialchars( $details['output'] ),
                    'raw' => $details
                );*/
				
				//$data = array(
				//'output' => htmlspecialchars( $details['output'] )
				//);
                
                /*if( $details['cmpinfo'] ) {
                    $data['cmpinfo'] = $details['cmpinfo'];
                }*/
                
				echo "Status: $status | Memory: {$details['memory']}<br>";
				echo "Returned value: {$details['status']} | Time: {$details['time']}s<br>";
				echo "OUTPUT<br>";
				//echo  $details['output'];
				//$var=htmlentities( $details['output'] );
				$var=nl2br($details['output']);
				$ans=str_replace("\t","&nbsp;",$var);
				echo $ans;
				
				if( $details['cmpinfo'] ) {
				echo htmlentities($details['cmpinfo']);
				}
                //echo json_encode( $data );
				//echo $data;
            } else {
                //we got some error :(
                //print_r( $details );
                echo json_encode( $error );
            }
        } else {
            //we got some error :(
            //print_r( $status );
            echo json_encode( $error );
        }
    } else {
        //we got some error :(
        //print_r( $result );
        echo json_encode( $error );
    }
//}
