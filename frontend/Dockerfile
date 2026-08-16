FROM node:20-alpine

WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN npm install

# Build the application
RUN npm run build

# Expose port
EXPOSE 3000

# Start the application
CMD ["node", "build"]
