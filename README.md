# Plugin Gemini para Moodle

Um plugin que permite aos usuários fazer perguntas ao Gemini dentro do Moodle.

## Funcionalidades

- 💬 Interface conversacional com layout de balões de chat
- ⚙️ Configurações de administrador: chave da API, temperatura, máximo de tokens, prompt do sistema
- 🔒 protegido por `require_login()` + `require_sesskey()`

---

## Requisitos

| Requisito | Versão |
|---|---|
| Moodle | 4.0 ou superior |
| PHP | 7.4 ou superior |
| Chave gratuita da API Gemini | [Obtenha gratuitamente](https://aistudio.google.com/app/apikey) |

---

## Instalação

1. Compacte a pasta `block_gemini_chat` em um arquivo ZIP.
2. Vá para **Administração do site → Plugins → Instalar plugins**.
3. Envie o arquivo ZIP e siga o assistente exibido na tela.
---

## Configuração

1. Vá para **Administração do site → Plugins → Blocos → Gemini AI Chat**.
2. Insira sua **Chave da API Gemini**.
3. Opcionalmente ajuste a **temperatura**, o **máximo de tokens** e o **prompt do sistema**.
4. Salve as alterações.

---

## Adicionando o bloco a um curso

1. Vá para a página de um curso e ative o modo de **edição**.
2. Clique em **Adicionar um bloco → Gemini AI Chat**.
3. O bloco aparecerá na barra lateral; os usuários poderão imediatamente digitar uma pergunta e clicar em **Enviar**.

---

## Observações de segurança

- A chave da API é armazenada na configuração do plugin do Moodle.
- Para uma boa pratica de segurança toda requisição feita valida a chave de sessão do Moodle (`sesskey`).
- A entrada do usuário não é armazenada; cada pergunta é uma requisição sem estado para a API Gemini.

