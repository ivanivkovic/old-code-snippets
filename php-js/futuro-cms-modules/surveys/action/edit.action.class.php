<?php

class SurveysEdit{

	public static function Run()
	{
		if( OUTPUT === 'html' )
		{
			# Insert/Update survey.
			if(isset($_POST['surveys_save']))
			{
				if(strcmp($_POST['surveys_save'], 'publish'))
				{
					############ ADD NEW / CREATE ###############
					
					if(isset($_POST['mode']) && $_POST['mode'] == 'create')
					{
						# Load survey title into array, perform check.
						if(isset($_POST['title']))
						{
							foreach($_POST['title'] as $key => $title)
							{
								if(strlen($title) < 1)
								{
									echo libJSON::FromArray(array('error' => 3, 'url' => '#!/surveys/list')); die();
								}
								else
								{
									$surveyTitles[$key] = $title;	
								}
							}
						}else
						{
							echo libJSON::FromArray(array('error' => 3, 'url' => '#!/surveys/list')); die();
						}
						
						# Load survey questions and options into array, perform check	
						if(isset($_POST['question']))
						{
							$questions = $_POST['question'];
						}
						else
						{
							echo libJSON::FromArray(array('error' => 3, 'url' => '#!/surveys/list')); die();
						}
						
						if(isset($questions) && isset($surveyTitles))
						{
							if($update = self::_insert($surveyTitles, $questions, (isset($_POST['status']) ? 1 : 0)))
							{
								echo libJSON::FromArray(array('error' => false, 'url' => '#!/surveys/edit/surveyid=' . $update)); die();
							}
							else
							{
								echo libJSON::FromArray(array('error' => 2)); die();
							}
						}
					}
					
					############# UPDATE/EDIT ############
					
					if(isset($_POST['mode']) && $_POST['mode'] == 'update' && isset($_POST['survey_id']) && is_numeric($_POST['survey_id']))
					{
						// Update current questions and delete non-existing.
						if(!self::_update($_POST['survey_id'], $_POST['title'], $_POST['question']))
						{
							echo libJSON::FromArray(array('error' => 2, 'url' => '#!/surveys/edit/surveyid=' . $_POST['survey_id'])); die();
						}
						else
						{
							if(!self::_togglePublish($_POST['survey_id'], (isset($_POST['status']) ? 1 : 0)))
							{
								echo libJSON::FromArray(array('error' => 2, 'url' => '#!/surveys/edit/surveyid=' . $_POST['survey_id'])); die();
							}
						}
						
						// Add newly created questions.
						if(isset($_POST['question']['new']))
						{
							if(!self::_addQuestions($_POST['survey_id'], $_POST['question']['new']))
							{
								echo libJSON::FromArray(array('error' => 2, 'url' => '#!/surveys/edit/surveyid=' . $_POST['survey_id'])); die();
							}
						}
						
						echo libJSON::FromArray(array('error' => false, 'url' => '#!/surveys/edit/surveyid=' . $_POST['survey_id'])); die();
					}
				}
			}
			else
			{
				// Load survey update form.
				if(isset($_GET['surveyid']))
				{
					libLoad::Api('apiSurveyLoad', 'surveys');
					
					if(apiSurveyLoad::surveyExists($_GET['surveyid']))
					{
						$tpl = new libTempleate('edit.tpl', 'surveys');
						$tpl->set('Data', apiSurveyLoad::loadCompleteSurvey($_GET['surveyid']));
						$tpl->set('Stats', apiSurveyLoad::loadResults($_GET['surveyid']));
					}
					else
					{
						$tpl = new libTempleate('add.tpl', 'surveys');
						$tpl->set('error', '<div class="inner-box"><h3>Žao nam je, ta anketa ne postoji. Možete kreirati novu.</h3></div>');
					}
				}
				else
				{
					$tpl = new libTempleate('add.tpl', 'surveys');
				}
				
				usrAdmin::SetCentralContent($tpl);
			}
		}
		else if( OUTPUT === 'ajax' )
		{
			# test
			usrAjax::SetOutputData( array( 'error' => false, 'test' => 'test'));
			
			if( isset($_GET['action']) )
			{
				switch($_GET['action'])
				{
					// complete survey deletion
					case 'delete':
					
						if(isset($_POST['id']) && is_numeric($_POST['id']) )
						{
							if(self::_delete($_POST['id']))
							{
								usrAjax::SetOutputData( array( 'error' => false));
							}
							else
							{
								usrAjax::SetOutputData( array( 'error' => 5));
							}
						}
						
					break;
					
					// status toggling
					case 'publish':
					
						if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['status']) && is_numeric($_POST['status']))
						{
							if(self::_togglePublish($_POST['id'], $_POST['status']))
							{
								usrAjax::SetOutputData( array( 'error' => false));
							}
							else
							{
								usrAjax::SetOutputData( array( 'error' => 2));
							}
						}
						
					break;
				}
			}
		}
	}
	
	# Toggles publishing.
	private static function _togglePublish($id, $status)
	{
		return FFCore::$Db->Query('UPDATE ?surveys SET active="' . $status . '" WHERE survey_id="' . $id . '"');
	}
	
	# Insert survey and all of it's content 
	private static function _insert($surveyTitles, $questions, $active){
		
		# Insert the survey.
		if(FFCore::$Db->InsertArray('?surveys', array('time' => libDateTime::Time(), 'active' => $active)))
		{
			$surveyId = FFCore::$Db->GetInsertId(); # Store survey id or false.
		}
		else
		{
			$surveyId = false;
		}
		
		# Translate survey.
		foreach($surveyTitles as $lang => $value)
		{
			libText::Update('survey', $surveyId, 'title', $value , $lang);
		}
		
		# Insert questions to survey.
		foreach($questions as $key => $value)
		{
			FFCore::$Db->InsertArray('?surveys_questions', array('survey_id' => $surveyId, 'option_limit' => $value['option_limit']));
			$questionIds[] = FFCore::$Db->GetInsertId(); # Fetch IDs.
		}
		
		foreach($questionIds as $key => $id)
		{
			# Translate questions.
			foreach($questions[$key]['title'] as $lang => $text)
			{
				libText::Update('survey_questions', $id, 'subtitle', $text, $lang);
			}
			
			$c = 0;
			
			foreach($questions[$key]['options'] as $lang => $optionsArray)
			{
				# Insert options.
				if($c === 0)
				{
					$numberOfOptions = count($optionsArray);
					
					for($i = 0; $i < $numberOfOptions; ++$i)
					{
						FFCore::$Db->InsertArray('?surveys_options', array('question_id' => $id));
						$optionIds[] = FFCore::$Db->GetInsertId();
					}
				}
				
				# Translate options.
				foreach($optionIds as $optionIdsKey => $optionId)
				{
					libText::Update('survey_options', $optionId, 'name', $optionsArray[$optionIdsKey + 1], $lang);
				}
				
				++$c;
			}
			
			unset($optionIds);
		}
		
		# Return survey id or false if survey insert failed.
		return $surveyId;
	}
	
	private static function _update($surveyId, $surveyTitles, $questions)
	{
		libLoad::Api('apiSurveyLoad', 'surveys');
		
		# Translate survey.
		foreach($surveyTitles as $lang => $value)
		{
			if(!libText::Update('survey', $surveyId, 'title', $value, $lang))
			{
				return false;
			}
		}
		
		// Option/question deletion.
		// Loop from db and scan it in $_POST. What does not exists in $_POST, delete it from db.
		$db_questions = FFCore::$Db->GetData('SELECT question_id FROM ?surveys_questions WHERE survey_id="' . $surveyId . '"');
		
		foreach($db_questions as $array)
		{
			$id = $array['question_id'];
			
			if(!isset($questions[$id]))
			{
				$options = FFCore::$Db->GetData('SELECT option_id FROM ?surveys_options WHERE question_id="' . $id . '"');
				
				foreach($options as $oArray)
				{
					self::_deleteQuestion($oArray['option_id']);
				}
				
				FFCore::$Db->Query('DELETE FROM ?surveys_questions WHERE question_id="' . $id . '"');
			}
			else
			{
				$options = FFCore::$Db->GetData('SELECT option_id FROM ?surveys_options WHERE question_id="' . $id . '"');
				
				foreach($options as $oArray)
				{
					if(!isset($questions[$id]['options'][$lang][$oArray['option_id']]))
					{
						self::_deleteOption($oArray['option_id']);
					}
				}
			}
		}
		
		/**
		* Loop questions/options from post, update all of their info to db. What does not exist, insert it.
		*/
		
		foreach($questions as $qId => $array)
		{
			if($qId != 'new')
			{
				$titles = $array['title'];
				
				// Update option limit.
				if(!FFCore::$Db->UpdateByArray('?surveys_questions', array('option_limit' => $array['option_limit']), 'question_id="' . $qId . '"'))
				{
					return false;
				}
				
				if(apiSurveyLoad::questionExists($qId))
				{
					foreach($titles as $lang => $text)
					{
						if(!libText::Update('survey_questions', $qId, 'subtitle', $text, $lang))
						{
							return false;
						}
					}
				}
				
				$options = $array['options'];
				
				$c = 0;
				
				foreach($options as $lang => $ids)
				{
					foreach($ids as $id => $text)
					{
						// Existing options, update text.
						if($id != 'new')
						{
							if(apiSurveyLoad::optionExists($id)) // Option exists, update text.
							{
								if(!libText::Update('survey_options', $id, 'name', $text, $lang))
								{
									return false;
								}
							}
						}
						else // Option does not exist, create it and translate it.
						{
							if($c === 0) // They're created once, regardless of the language count. (language loop)
							{
								$count = count($text);
								
								for($i = 0; $i < $count; ++$i)
								{
									if(FFCore::$Db->InsertArray('?surveys_options', array('question_id' => $qId)))
									{
										$newIds[] = FFCore::$Db->GetInsertId();
									}
									else
									{
										return false;
									}
								}
							}
							
							// Translate each new option to the $lang
							foreach($newIds as $key => $nid)
							{
								if(!libText::Update('survey_options', $nid, 'name', $text[$key + 1], $lang))
								{
									return false;
								}
							}
						}
					}
					++$c;
				}
			}
		}
		
		return true;
	}
	
	private static function _deleteQuestion($questionId)
	{
		FFCore::$Db->Query('DELETE FROM ?surveys_options WHERE option_id="' . $questionId . '"');
		FFCore::$Db->Query('DELETE FROM ?surveys_answers WHERE option_id="' . $questionId . '"');
		
		libText::Delete('surveys_options', $questionId);
	}
	
	private static function _deleteOption($optionId)
	{
		FFCore::$Db->Query('DELETE FROM ?surveys_options WHERE option_id="' . $optionId . '"');
		FFCore::$Db->Query('DELETE FROM ?surveys_answers WHERE option_id="' . $optionId . '"');
		
		libText::Delete('surveys_options', $optionId);
	}
	
	private static function _addQuestions($surveyId, $questions){
	
		# Insert questions to survey.
		foreach($questions as $array)
		{
			if(FFCore::$Db->InsertArray('?surveys_questions', array('survey_id' => $surveyId, 'option_limit' => $array['option_limit'])))
			{
				$questionIds[] = FFCore::$Db->GetInsertId(); # Fetch IDs.
			}
			else
			{
				return false;
			}
		}
		
		foreach($questionIds as $key => $id)
		{
			# Translate questions.
			foreach($questions[$key]['title'] as $lang => $text)
			{
				if(!libText::Update('survey_questions', $id, 'subtitle', $text, $lang))
				{
					return false;
				}
			}
			
			$c = 0;
			
			foreach($questions[$key]['options'] as $lang => $optionsArray)
			{
				# Insert options.
				if($c === 0)
				{
					$numberOfOptions = count($optionsArray);
					
					for($i = 0; $i < $numberOfOptions; ++$i)
					{
						if(FFCore::$Db->InsertArray('?surveys_options', array('question_id' => $id)))
						{
							$optionIds[] = FFCore::$Db->GetInsertId();
						}
						else
						{
							return false;
						}
					}
				}
				
				# Translate options.
				foreach($optionIds as $optionIdsKey => $optionId)
				{
					if(!libText::Update('survey_options', $optionId, 'name', $optionsArray[$optionIdsKey + 1], $lang))
					{
						return false;
					}
				}
				
				++$c;
			}
			
			unset($optionIds);
		}
		
		return true;
	}
	
	/**
	* @param int $surveyId
	* @return bool
	* Deletes a survey and all it's data.
	*/
	
	private static function _delete($surveyId)
	{
		if(FFCore::$Db->query('DELETE FROM ?surveys WHERE survey_id="' . $surveyId . '"'))
		{
			libText::Delete('survey', $surveyId);
			$questions = FFCore::$Db->GetData('SELECT question_id FROM ?surveys_questions WHERE survey_id="' . $surveyId . '"');
			
			if($questions !== false)
			{
				foreach($questions as $question)
				{
					$options = FFCore::$Db->GetData('SELECT option_id FROM ?surveys_options WHERE question_id="' . $question['question_id'] . '"');
					
					foreach($options as $option)
					{
						if(
							FFCore::$Db->Query('DELETE FROM ?surveys_options WHERE option_id="' . $option['option_id'] . '"') && 
							FFCore::$Db->Query('DELETE FROM ?surveys_answers WHERE option_id="' . $option['option_id'] . '"')
						){
							libText::Delete('survey_options', $option['option_id']);
						}
						else
						{
							return false;
						}
					}
					
					$success = FFCore::$Db->Query('DELETE FROM ?surveys_questions WHERE question_id="' . $question['question_id'] . '"');
					
					if($success)
					{
						libText::Delete('survey_questions', $question['question_id']);
					}
					else{ return false; } 
				}
				
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
}

SurveysEdit::Run();