FROM node:12.14.0-slim as build

RUN ["mkdir", "/frontend"]
WORKDIR /frontend
COPY package*.json ./
RUN ["npm", "ci"]
COPY . .
ARG firebase_api_key
ARG sentry_dsn
ENV SENTRY_DSN=$sentry_dsn
ENV NUXT_ENV_FIREBASE_API_KEY=$firebase_api_key
RUN ["npm", "run", "build"]

FROM node:12.14.0-slim
RUN ["mkdir", "/app"]
WORKDIR /app
COPY --from=build /frontend/package*.json /app/
RUN ["npm", "ci", "--only=production"]
COPY --from=build /frontend/src/static /app/src/static
COPY --from=build /frontend/.nuxt /app/.nuxt
COPY --from=build /frontend/nuxt.config.js /app/
EXPOSE 3000
CMD ["npm", "run", "start"]
