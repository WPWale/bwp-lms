<?php

return $questions = array(
	array(
		'stem' => '',
		'options' => array(),
		'key' => array(),
		'type' => 'mcq',
		'composite' => false,
		'questions' => array(),
	),
	// multicheckbox
	'mcq' => array(
		'stem' => '',
		'options' => array( '' ),
		'key' => 0 //or array when it is a multiple
	),
	// single checkbox
	'boolean' => array(
		'stem' => '',
		// same as mcq, no distractors
		'key' => false
	),
	// three state toggle, so false states have to be explicitly chosen
	'assertion-reason' => array(
		'assertion' => '',
		'reasons' => '',
		'key' => array(
			'lcolumn' => false,
			'rcolumn' => false,
			'because' => false,
		),
	),
	'matching' => array(
		'stem' => '',
		'stems' => array(
			array(
				'stem' => '',
				'score' => 0,
				'key' => false,
				),
		),
		'options' => array(),
		'key' => array(),
	),
	'input' => array(
		'stem' => '',
		'hotspots' => array(),
		// same as mcq, no distractors
		'key' => '', // can be a regex
	),
	'hotspot' => array(
		'stem' => '',
		'hotspots' => array(),
		// same as mcq, no distractors
		'key' => array(),
	),
	'order' => array(
		'stem' => '',
		'distractors' => array(
			array(
				'stem' => '',
				'order' => 0
			),
		),
		'key' => array(
			array(
				'stem' => '',
				'order' => 0
			),
		), //same as list
	),
	'list' => array(
		'stem',
		'columns',
		'key' => array(),
	)
);

/*
 * Match the following is like multiple mcqs in one
 * where each left column statement is a mcq stem
 * and the right column has the options common between all left column qns.
 * 
 * assertion reason (because questions) are also mcqs with all combinations of assertion, reason and causation boolean states as options
 * 
 * true/ false questions are just MCQs with true/false/NULL options
 * 
 * hotspot is just a combination of true/false questions (where clicking the hotspot changes state from false to true)
 * 
 * hotspot with input is a combination of true/false & fill in the blank
 * 
 * There are only two kinds of questions:
 *  1. Choice
 *  2. Input Supply
 *  3. A combination of Choice & Input Supply
 * 
 * T/F Questions are questions with 2 choices, T or F
 * 
 * MCQs are questions with more choices
 * 
 * Assertion Reason are a composite question with three T/F Questions
 * 
 * Match the following is a composite qn with multiple MCQs sharing the same options
 * 
 * Hotspot (sore finger) question is also a composite of either multiple T/F questions
 *  or multiple input questions
 * 
 * 
 */