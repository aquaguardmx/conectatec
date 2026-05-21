FROM node:22.18.0-bookworm

WORKDIR /app

COPY package*.json ./

RUN npm install --include=optional
RUN npm install @nuxtjs/tailwindcss tailwindcss postcss autoprefixer @oxc-parser/binding-linux-x64-gnu @oxc-transform/binding-linux-x64-gnu --save-dev

COPY . .

RUN npm run build

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]