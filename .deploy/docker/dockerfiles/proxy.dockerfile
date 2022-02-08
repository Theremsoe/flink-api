#######################################
# Proxy
#######################################
FROM nginx as proxy
RUN rm /etc/nginx/nginx.conf
COPY .deploy/nginx/nginx.conf /etc/nginx/
RUN rm /etc/nginx/conf.d/default.conf
COPY .deploy/nginx/api.conf /etc/nginx/conf.d/
