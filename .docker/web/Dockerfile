FROM node:18-alpine

WORKDIR /app
COPY ./web .

RUN npm install

RUN npm run build

CMD [ "npm", "run", "preview" ]