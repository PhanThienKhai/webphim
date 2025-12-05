(function (config) {
  if (document.getElementById("chatContainer")) return;

  let chatStyle = document.createElement("link");
  chatStyle.rel = "stylesheet";
  chatStyle.href = "https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css";
  document.head.appendChild(chatStyle);

  let chatContainer = document.createElement("div");
  chatContainer.id = "chatContainer";
  document.body.appendChild(chatContainer);

  let customStyle = document.createElement("style");
  customStyle.innerHTML = `
  :root {
    --chat--color-primary: #2563eb;
    --chat--color-primary-hover: #1d4ed8;
    --chat--color-primary-active: #1e40af;
    --chat--color-accent: #3b82f6;
    --chat--color-white: #ffffff;
    --chat--color-light: #f8fafc;
    --chat--color-dark: #1e293b;
    --chat--color-grey: #64748b;
    --chat--color-border: #e2e8f0;
    --chat--color-success: #10b981;
    --chat--border-radius: 16px;
    --chat--window--width: 420px;
    --chat--window--height: 600px;
    --chat--toggle--size: 56px;
    --chat--font-size: 15px;
  }

  .chat-window-wrapper {
    bottom: 2rem;
    right: 2rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
    border-radius: var(--chat--border-radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--chat--color-border);
    background: var(--chat--color-white);
    animation: slideIn 0.3s ease-out;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  #chatContainer .chat-heading {
    font-size: 10px;
    padding: 4px 10px;
    background: linear-gradient(135deg, #333435ff 0%, #27304eff 100%);
    color: var(--chat--color-white);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    border-radius: 0;
  }

  #chatContainer .chat-heading::before {
    content: "💬";
    font-size: 20px;
    display: inline-block;
    animation: bounce 2s infinite;
  }

  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
  }

  #chatContainer .chat-body {
    flex: 1;
    background: var(--chat--color-light);
    padding: 2px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    scrollbar-width: thin;
    scrollbar-color: var(--chat--color-accent) transparent;
  }

  #chatContainer .chat-body::-webkit-scrollbar {
    width: 6px;
  }

  #chatContainer .chat-body::-webkit-scrollbar-track {
    background: transparent;
  }

  #chatContainer .chat-body::-webkit-scrollbar-thumb {
    background: var(--chat--color-accent);
    border-radius: 3px;
  }

  #chatContainer .chat-message {
    padding: 5px 2px;
    font-size: var(--chat--font-size);
    border-radius: 12px;
    background: var(--chat--color-white);
    color: var(--chat--color-dark);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    max-width: 85%;
    line-height: 1.5;
    transition: all 0.3s ease;
    word-wrap: break-word;
    word-break: break-word;
  }

  #chatContainer .chat-message:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  #chatContainer .chat-message.bot {
    background: #f1f5f9;
    border-left: 3px solid var(--chat--color-accent);
    margin-right: auto;
  }

  #chatContainer .chat-message.user {
    background: var(--chat--color-accent);
    color: var(--chat--color-white);
    margin-left: auto;
    border-left: none;
  }

  #chatContainer .chat-input {
    padding: 8px;
    border-top: 1px solid var(--chat--color-border);
    background: var(--chat--color-white);
    display: flex;
    gap: 10px;
    align-items: flex-end;
  }

  #chatContainer .chat-input input,
  #chatContainer .chat-input textarea {
    flex: 1;
    background-color: var(--chat--color-light);
    color: var(--chat--color-dark);
    border: 1.5px solid var(--chat--color-border);
    padding: 11px 14px;
    font-size: var(--chat--font-size);
    border-radius: 10px;
    outline: none;
    transition: all 0.3s ease;
    font-family: inherit;
    resize: none;
    max-height: 100px;
  }

  #chatContainer .chat-input input::placeholder,
  #chatContainer .chat-input textarea::placeholder {
    color: var(--chat--color-grey);
  }

  #chatContainer .chat-input input:focus,
  #chatContainer .chat-input textarea:focus {
    border-color: var(--chat--color-accent);
    background-color: var(--chat--color-white);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  #chatContainer .chat-input button {
    background: linear-gradient(135deg, #6d7da0 0%, #000000 100%);
    border: none;
    color: white;
    padding: 11px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  }

  #chatContainer .chat-input button:hover {
    background: linear-gradient(135deg, var(--chat--color-primary-hover) 0%, var(--chat--color-primary) 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
  }

  #chatContainer .chat-input button:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
  }

  #chatContainer .chat-toggle {
    width: var(--chat--toggle--size);
    height: var(--chat--toggle--size);
    background: linear-gradient(135deg, var(--chat--color-primary) 0%, var(--chat--color-accent) 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 28px;
    font-weight: bold;
  }

  #chatContainer .chat-toggle:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.4);
  }

  #chatContainer .chat-toggle:active {
    transform: scale(0.96);
  }

  .chat-layout .chat-header {
    height: var(--chat--header-height, 60px);
    padding: var(--chat--header--padding, 16px 20px);
    background: var(--chat--header--background, linear-gradient(135deg, #2563eb 0%, #1e40af 100%));
    color: var(--chat--header--color, #ffffff);
    border-top: var(--chat--header--border-top, none);
    border-bottom: var(--chat--header--border-bottom, 1px solid #e2e8f0);
    border-left: var(--chat--header--border-left, none);
    border-right: var(--chat--header--border-right, none);
    flex-direction: column;
    justify-content: center;
    gap: 0px;
    display: flex;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    font-weight: 600;
    height: 100px;
  }

  /* Responsive Design */
  @media (max-width: 640px) {
    :root {
      --chat--window--width: calc(100vw - 24px);
      --chat--window--height: 70vh;
      --chat--toggle--size: 52px;
      --chat--font-size: 14px;
    }

    .chat-window-wrapper {
      bottom: 1rem;
      right: 0.75rem;
    }

    #chatContainer .chat-heading {
      padding: 14px 16px;
      font-size: 15px;
    }

    #chatContainer .chat-body {
      padding: 12px;
    }

    #chatContainer .chat-input {
      padding: 12px;
    }
  }
.chat-window-wrapper .chat-window-toggle {
    background: #5a80d8;
    color: var(--chat--toggle--color);
    cursor: pointer;
    width: 61px;
    height: 55px;
    border-radius: var(--chat--toggle--border-radius, 50%);
    transition: transform var(--chat--transition-duration) 
ease, background var(--chat--transition-duration) 
ease;
    flex: none;
    justify-content: center;
    align-items: center;
    margin-left: auto;
    display: inline-flex;
}
  .chat-messages-list {
    padding: 32px;
    margin-top: auto;
    display: block;
    margin-left: -19px;
    font-size: 14px;
}
    .chat-message>.chat-message-markdown>:last-child {
    margin-bottom: 0;
        font-size: 17px;

}
  `;
  document.head.appendChild(customStyle);

  let chatScript = document.createElement("script");
  chatScript.type = "module";
  chatScript.src = "https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js";
  chatScript.onload = function () {
    import("https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js").then(({ createChat }) => {
      createChat({
        mode: "window",
        target: "#chatContainer",
        webhookUrl: config.webhookUrl,
        showWelcomeScreen: false,
        loadPreviousSession: false,
        allowFileUploads: false,
        allowedFilesMimeTypes: "*",
        i18n: {
          en: {
            title: config.title || "Trợ lý AI VIP ✨",
            subtitle: config.subtitle || "Chat với tôi bất cứ lúc nào!",
            inputPlaceholder: "Nhập tin nhắn của bạn...",
          },
        },
        initialMessages: [config.welcomeBot, config.messageBot],
      });
    });
  };

  document.body.appendChild(chatScript);
})({
  title: "Membee Chat",
  subtitle: "Chatbot hỗ trợ 24/7",
  webhookUrl: "",
  welcomeBot: "",
  messageBot: ""
});
