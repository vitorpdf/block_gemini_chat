<?php



// Plugin strings (English).
$string['pluginname']              = 'Gemini Chat';
$string['gemini_chat:addinstance'] = 'Add a Gemini AI Chat block';
$string['gemini_chat:myaddinstance']= 'Add a Gemini AI Chat block to My Moodle';

// Block UI.
$string['send']                    = 'Send';
$string['questionplaceholder']     = 'Ask anything…';
$string['questionlabel']           = 'Your question';
$string['chathistory']             = 'Chat history';
$string['welcomemsg']              = 'Hello! I am your AI assistant powered by Gemini. Ask me anything!';
$string['you']                     = 'You';
$string['ai']                      = 'Gemini AI';
$string['errornoapikey']           = 'The Gemini API key is not configured. Please ask a site administrator to set it up.';
$string['erroremptyquestion']      = 'Please type a question before sending.';
$string['errorgeneral']            = 'An error occurred while contacting Gemini. Please try again.';
$string['errorsesskey']            = 'Session key mismatch. Please refresh the page.';

// Admin settings.
$string['settings']                = 'Gemini AI Chat Settings';
$string['apikey']                  = 'Gemini API Key';
$string['apikey_desc']             = 'Enter your Google Gemini API key. You can obtain one at <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>.';
$string['model']                   = 'Gemini Model';
$string['maxoutputtokens']         = 'Max output tokens';
$string['maxoutputtokens_desc']    = 'Maximum number of tokens in the AI response (100–8192).';
$string['temperature']             = 'Temperature';
$string['temperature_desc']        = 'Controls creativity of responses. Lower = more deterministic, Higher = more creative (0.0–1.0).';
$string['systemprompt']            = 'System prompt';
$string['systemprompt_desc']       = 'Optional instruction sent to Gemini before every user message (e.g. "You are a helpful tutor.").';
