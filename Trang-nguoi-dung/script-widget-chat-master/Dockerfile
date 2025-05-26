FROM --platform=linux/amd64 node:18.19.0 as builder
WORKDIR /app
COPY package.json /app
RUN npm install
COPY . .

EXPOSE 3000
CMD ["node", "index.js"]