FROM node:22.18.0-bookworm

WORKDIR /app

COPY package*.json ./

RUN npm install --include=optional

COPY . .

RUN npm run build

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]