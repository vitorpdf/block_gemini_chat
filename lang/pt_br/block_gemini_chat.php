<?php

// Strings do plugin (Português - Brasil).
$string['pluginname']              = 'IA Gemini';
$string['gemini_chat:addinstance'] = 'Adicionar um bloco de Chat IA Gemini';
$string['gemini_chat:myaddinstance']= 'Adicionar um bloco do Chat Gemini ao Meu Moodle';

// Interface do bloco.
$string['send']                    = 'Enviar';
$string['questionplaceholder']     = 'Faça uma pergunta…';
$string['questionlabel']           = 'Sua pergunta';
$string['chathistory']             = 'Histórico do chat';
$string['welcomemsg']              = 'Olá! Sou seu assistente. Pergunte o que quiser!';
$string['you']                     = 'Você';
$string['ai']                      = 'Gemini';

// Erros.
$string['errornoapikey']           = 'A chave de API do Gemini não está configurada. Solicite ao administrador do site que a configure.';
$string['erroremptyquestion']      = 'Por favor, digite uma pergunta antes de enviar.';
$string['errorgeneral']            = 'Ocorreu um erro ao contatar o Gemini. Tente novamente.';
$string['errorsesskey']            = 'Chave de sessão inválida. Atualize a página e tente novamente.';

// Configurações do administrador.
$string['settings']                = 'Configurações do Chat com Gemini';
$string['apikey']                  = 'Chave de API do Gemini';
$string['apikey_desc']             = 'Insira sua chave de API do Google Gemini. Você pode obtê-la gratuitamente em <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>.';
$string['model']                   = 'Modelo Gemini';
$string['maxoutputtokens']         = 'Máximo de tokens na resposta';
$string['maxoutputtokens_desc']    = 'Número máximo de tokens na resposta da IA (100–8192).';
$string['temperature']             = 'Temperatura';
$string['temperature_desc']        = 'Controla a criatividade das respostas. Menor = mais determinístico, Maior = mais criativo (0,0–1,0).';
$string['systemprompt']            = 'Prompt do sistema';
$string['systemprompt_desc']       = 'Instrução opcional enviada ao Gemini antes de cada mensagem do usuário (ex.: "Você é um tutor x.").';
