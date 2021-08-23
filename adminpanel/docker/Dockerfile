FROM node:12.14.0-slim as build

RUN ["mkdir", "/frontend"]

WORKDIR /frontend

COPY ./package*.json ./

RUN ["npm", "ci"]

COPY . .

RUN ["npm", "run", "build"]

FROM nginx:1.17

COPY --from=build /frontend/dist /app

COPY ./docker/nginx.conf /etc/nginx/nginx.conf
