FROM node:18-alpine

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci --legacy-peer-deps

COPY . .

RUN npm run build

RUN npm install -g serve

EXPOSE 3001

CMD ["serve", "-s", "dist", "-p", "3001"]
