FROM node:latest as build-stage

WORKDIR /app

COPY ./web .

COPY package*.json ./

RUN npm install

RUN npm run build

# Production stage
FROM nginx:latest

# Copy build files from the previous stage
COPY --from=build-stage /app/dist /usr/share/nginx/html

# Start Nginx
CMD ["npm", "run", "dev"]