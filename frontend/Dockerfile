FROM node:22.18.0-bookworm

WORKDIR /app

COPY package*.json ./

RUN npm install --include=optional

COPY . .

# Build con variables de entorno
ARG NUXT_PUBLIC_API_BASE=http://localhost:8000
ENV NUXT_PUBLIC_API_BASE=${NUXT_PUBLIC_API_BASE}

RUN npm run build

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]