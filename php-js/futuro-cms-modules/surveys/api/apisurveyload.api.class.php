<?php

class ObjectSurvey{
	
	public $id;
	public $title;
	public $time;
	public $active;
	public $questions;
	
}

class apiSurveyLoad{
	
	/**
	*
	* @param int $id
	* @param string|array $lang
	*
	* @return array|object|bool
	*
	* If input language is a string 'en', it will return an object containing english survey data.
	* If input language is an array('en', 'de'), it will return an array of objects ex. $survey['en'] = object.
	*/

	public static function loadCompleteSurvey($id, $langs = array())
	{
		if(self::surveyExists($id))
		{
			if(is_string($langs)){
				
				$lang = $langs;
				
				$survey = new ObjectSurvey();
				
				$survey->id = $id;
				$survey->active = self::getSurveyStatus($id);
				$survey->title = self::getSurveyTitle($id, $langs);
				$survey->time = self::getSurveyTime($id);
				$survey->latest_answer_time = self::getLatestAnswerTime($id);
				$survey->questions = self::getSurveyQuestions($id, $lang);
				
				foreach($survey->questions as $qID => $title)
				{
					$survey->questions[$qID]['options'] = self::getQuestionOptions($qID, $langs);
				}
			}
			else
			{
				if(empty($langs))
				{
					$langs = self::getPostedLanguages();
				}
				
				foreach($langs as $lang)
				{
					$survey[$lang] = new ObjectSurvey();
					
					$survey[$lang]->id = $id;
					$survey[$lang]->active = self::getSurveyStatus($id);
					$survey[$lang]->time = self::getSurveyTime($id);
					$survey[$lang]->title = self::getSurveyTitle($id, $lang);
					$survey[$lang]->latest_answer_time = self::getLatestAnswerTime($id);
					$survey[$lang]->questions = self::getSurveyQuestions($id, $lang);
					
					foreach($survey[$lang]->questions as $qID => $title)
					{
						$survey[$lang]->questions[$qID]['options'] = self::getQuestionOptions($qID, $lang);
					}
				}
			}
			
			return $survey;
		}
		else
		{
			return false;
		}
	}
	
	public static function getLatestAnswerTime($id)
	{
		$result = FFCore::$Db->GetOne('SELECT MAX(time) AS time FROM ?surveys_answers WHERE survey_id="' . $id . '"');
		return $result['time'];
	}
	
	/**
	* Returns array of objects made by loadCompleteSurvey() - sorted by language.
	*
	* @param string $lang language key
	* @return array
	*/
	
	public static function loadActiveSurveys($lang)
	{
		$surveys = FFCore::$Db->GetData('SELECT survey_id FROM ?surveys WHERE active="1"');
		
		if(!empty($surveys))
		{
			foreach($surveys as $survey)
			{
				$data[] = self::loadCompleteSurvey($survey['survey_id'], $lang);
			}
			
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* Returns survey questions and options with their titles, numeric and percentage statuses.
	* @return array|bool
	*/
	
	public static function loadResults($surveyId)
	{
		$result = FFCore::$Db->GetData('SELECT question_id FROM ?surveys_questions WHERE survey_id="' . $surveyId . '" ORDER BY question_id ASC');
		
		if(!empty($result))
		{
			foreach($result as $array)
			{
				$questions[$array['question_id']] = array();
			}
			
			foreach($questions as $qId => $qArray)
			{
				$result = FFCore::$Db->GetData('SELECT option_id FROM ?surveys_options WHERE question_id="' . $qId . '"  ORDER BY option_id ASC');
				
				if(!empty($result))
				{
					$parts = 0;
					
					foreach($result as $array)
					{
						$questions[$qId]['options'][$array['option_id']]['number'] = self::loadOptionSelectedCount($array['option_id']); 
						$parts = $parts + self::loadOptionSelectedCount($array['option_id']);
					}
					
					if($parts !== 0)
					{
						$slice = 100 / $parts;
						
						// Calculate percentage for each
						foreach($result as $array)
						{
							$questions[$qId]['options'][$array['option_id']]['percentage'] = round($questions[$qId]['options'][$array['option_id']]['number'] * $slice, 2);
							$success = true;
						}
					}
					else
					{
						foreach($result as $array)
						{
							$questions[$qId]['options'][$array['option_id']]['percentage'] = 0;
							$questions[$qId]['options'][$array['option_id']]['number'] = 0;
						}
					}
				}
			}
		}
		
		if(!isset($success)){ return false; }
		
		return $questions;
	}
	
	/**
	* @param int $optionId
	* @return int|bool - failed query/count of votes for the question
	*/
	
	public static function loadOptionSelectedCount($optionId)
	{
		$result = FFCore::$Db->GetOne('SELECT COUNT(answer_id) AS result_count FROM ?surveys_answers WHERE option_id="' . $optionId . '"');
		
		if($result !== false)
		{
			return $result['result_count'];
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @return array|bool
	*/
	
	public static function getPostedLanguages()
	{
		if(self::getSurveysCount() != 0)
		{
			$langs = FFCore::$Db->GetData('SELECT DISTINCT lang FROM ?text WHERE datafrom="survey"');
			
			foreach($langs as $key => $value)
			{
				$array[$key] = $value['lang'];
			}
			
			return $array;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @return int
	*/
	
	public static function getSurveysCount()
	{
		$result = FFCore::$Db->GetOne('SELECT COUNT(survey_id) AS num_rows FROM ?surveys');
		
		if($result !== false)
		{
			return $result['num_rows'];
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @param int $id
	* @param array $lang
	*
	* @return array
	*/
	
	public static function getSurveys($offset, $limit, $orderby, $order)
	{
		$rows = FFCore::$Db->GetData('SELECT ?surveys.survey_id as id, ?surveys.time, ?surveys.active FROM ?surveys ORDER BY ' . $orderby . ' ' . $order . ' LIMIT ' . $limit . ' OFFSET ' . $offset);

		foreach($rows as $key => $value)
		{
			$rows[$key]['title'] = libText::LoadOne('survey', $value['id'], 'title');
			$rows[$key]['count'] = self::getQuestionCount($value['id']);
			$time = self::getLatestAnswerTime($rows[$key]['id']);
			$rows[$key]['latest_answer_time'] =  $time != 0 ? libDateTime::Date('H:j', $time) : 0;
			$rows[$key]['latest_answer_date'] = $time != 0 ? libDateTime::Date('m. d. Y.', $time) : 0;
		}
		
		return $rows;
	}
	
	/**
	* @return int
	*/
	
	public static function getQuestionCount($surveyId)
	{
		$result = FFCore::$Db->GetOne('SELECT COUNT(question_id) AS count FROM ?surveys_questions WHERE survey_id="' . $surveyId . '"');
		return $result['count'];
	}
	
	/**
	* @return array
	*/
	
	public static function getSurveyQuestions($id, $lang = '')
	{
		$rows = FFCore::$Db->GetData('SELECT question_id, option_limit FROM ?surveys_questions WHERE survey_id="' . $id . '" ORDER BY question_id ASC');
		
		foreach($rows as $key => $value)
		{
			# If titles requested, return titles and IDs.
			if($lang !== '')
			{
				$questions[$value['question_id']]['option_limit'] = $value['option_limit'];
				$questions[$value['question_id']]['title'] = libText::LoadOne('survey_questions', $value['question_id'], 'subtitle', $lang);
			}
			
			# If titles NOT requested, just return IDs.
			else
			{
				$questions[] = $value['question_id'];
			}
		}
		
		return $questions;
	}
	
	/**
	* @return string (int)
	*/
	
	public static function getQuestionOptionLimit($qId)
	{
		$result = FFCore::$Db->GetOne('SELECT option_limit FROM ?surveys_questions WHERE question_id="' . $qId . '"');
		return ($result !== false ? $result['option_limit'] : false);
	}
	
	/**
	* @param int $id
	* @param array $lang
	*
	* @return array
	*/
	
	public static function getSurveyTitle($id, $lang)
	{
		return libText::LoadOne('survey', $id, 'title', $lang);
	}
	
	/**
	* @param int $id
	* @return int|bool $time
	*/
	
	public static function getSurveyTime($id)
	{
		$result = FFCore::$Db->GetOne('SELECT time FROM ?surveys WHERE survey_id="' . $id . '"');
		
		if($result !== false)
		{
			return $result['time'];
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @param int $id
	* @param array $lang
	*
	* @return array
	*/
	
	public static function getQuestionOptions($id, $lang)
	{
		$rows = FFCore::$Db->GetData('SELECT option_id FROM ?surveys_options WHERE question_id="' . $id . '" ORDER BY option_id ASC');
		$options = array();
		
		foreach($rows as $key => $value)
		{
			$options[$value['option_id']]['title'] = libText::LoadOne('survey_options', $value['option_id'], 'name', $lang);
		}
		
		return $options;
	}
	
	/**
	* @param int $id
	* @return bool
	*/
	
	public static function surveyExists($id)
	{
		if(FFCore::$Db->GetOne('SELECT survey_id FROM ?surveys WHERE survey_id="' . $id . '"') !== false)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	* @param int $id
	* @return bool
	*/
	
	public static function questionExists($id)
	{
		if(FFCore::$Db->GetOne('SELECT question_id FROM ?surveys_questions WHERE question_id="' . $id . '"') !== false)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	* @param int $id
	* @return bool
	*/
	
	public static function optionExists($id)
	{
		if(FFCore::$Db->GetOne('SELECT option_id FROM ?surveys_options WHERE option_id="' . $id . '"') !== false)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	* @ return int 0/1
	*/
	
	public static function getSurveyStatus($id)
	{
		$data = FFCore::$Db->GetOne('SELECT active FROM ?surveys WHERE survey_id="' . $id . '"');
		return $data['active'];
	}
	
	/**
	* @return bool
	*/
	
	public static function surveyUserExists()
	{
		if(isset($_SESSION['survey_user']))
		{
			if(FFCore::$Db->GetOne('SELECT user_id FROM ?surveys_users WHERE user_id="' . $_SESSION['survey_user'] . '" AND ip="' . $_SERVER['REMOTE_ADDR'] . '"') !== false)
			{
				return true;
			}
			
			return false;
		}
		
		return false;
	}
	
	/**
	* Creates a user if does not exist, returns his id.
	* @return int user_id
	*/
	
	public static function getSurveyUser()
	{
		if(!self::surveyUserExists())
		{
			FFCore::$Db->InsertArray('?surveys_users', array('ip' => $_SERVER['REMOTE_ADDR']));
			$result = FFCore::$Db->GetInsertId();
			
			if($result !== false)
			{
				$_SESSION['survey_user'] = $result;
			}
		}
		
		return $_SESSION['survey_user'];
	}
	
	/**
	* @return bool
	*/
	
	public static function userAnswered($userId, $surveyId)
	{
		if(!FFCore::$Db->GetOne('SELECT answer_id FROM ?surveys_answers WHERE user_id="' . $userId . '" AND survey_id="' . $surveyId . '"'))
		{
			return false;
		}
		
		return true;
	}
	
	/** 
	*
	* @return bool
	* Submits a user's answer.
	*
	*/
	
	public static function setAnswer($userId, $surveyId, $optionId)
	{
		if(FFCore::$Db->InsertArray('?surveys_answers', array('user_id' => $userId, 'survey_id' => $surveyId, 'option_id' => $optionId, 'time' => libDateTime::Time())))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	*
	* Processes the survey user and his input. Submits the survey answer, responds with message code.
	*
	* @return int message code
	*
	* CODES:
	*
	* 0 => Success.
	* 1 => Already answered.
	* 2 => Incomplete user input.
	* 3 => Survey does not exist.
	* 4 => Smijete odabrati samo x pitanja.
	* 5 => Something went wrong.
	*/
	
	public static function submitSurvey()
	{
		if(isset($_POST['survey']))
		{
			if(self::surveyExists($_POST['survey']))
			{
				if(!self::userAnswered(self::getSurveyUser(), $_POST['survey']))
				{
					if(isset($_POST['question']))
					{
						// Validate if all questions were answered.
						foreach(self::getSurveyQuestions($_POST['survey']) as $qId)
						{
							if(!isset($_POST['question'][$qId])) { return 2; }
						}
						
						foreach($_POST['question'] as $questionId => $value)
						{
							$userId = self::getSurveyUser();
							
							// Radio submit
							if(!is_array($value))
							{
								if(!self::setAnswer($userId, $_POST['survey'], $value)){ return 5; }
							}
							else 
							// Checkbox submit
							{
								if( count($value) > self::getQuestionOptionLimit($questionId) ){ return 4; }
								
								foreach($value as $oId)
								{
									if(!self::setAnswer($userId, $_POST['survey'], $oId)){ return 5; }
								}
							}
						}
					}
					else
					{
						return 2;
					}
				}
				else
				{
					return 1;
				}
			}
			else
			{
				return 3;
			}
		}
		else
		{
			return 5;
		}
		
		return 0;
	}
	
}