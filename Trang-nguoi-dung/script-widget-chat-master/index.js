const http = require("http");
const fs = require("fs");
const path = require("path");
const url = require("url");
const PORT = 3000;

const server = http.createServer((req, res) => {
  if (req.url.startsWith("/bot.js")) {
    const queryObject = url.parse(req.url, true).query;
    const filePath = path.join(__dirname, "./chatbot/bot.js");
    fs.readFile(filePath, "utf8", (err, data) => {
      if (err) {
        res.writeHead(500, { "Content-Type": "text/plain" });
        res.end("Internal Server Error");
        return;
      }

      // Chèn các tham số vào tệp bot.js
      const title = queryObject.title;
      const subtitle = queryObject.subtitle;
      const webhookUrl = queryObject.webhookUrl;
      const welcomeBot = queryObject.welcomeBot;
      const messageBot = queryObject.messageBot;
      data = data.replace(/config\.title/g, `"${title}"`);
      data = data.replace(/config\.subtitle/g, `"${subtitle}"`);
      data = data.replace(/config\.webhookUrl/g, `"${webhookUrl}"`);
      data = data.replace(/config\.welcomeBot/g, `"${welcomeBot}"`);
      data = data.replace(/config\.messageBot/g, `"${messageBot}"`);
      res.writeHead(200, { "Content-Type": "application/javascript" });
      res.end(data);
    });
  } else {
    res.writeHead(404, { "Content-Type": "text/plain" });
    res.end("Not Found");
  }
});

server.listen(PORT, () => {
  console.log(`Server đang chạy tại: http://localhost:${PORT}`);
});
