FROM nginx:1.21-alpine

# Remove any problematic custom.conf file or directory that might be present by default
RUN rm -rf /etc/nginx/conf.d/custom.conf

# Copy your default.conf into the Nginx configuration directory
COPY default.conf /etc/nginx/conf.d/default.conf
