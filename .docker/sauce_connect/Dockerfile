FROM alpine:3.5

# replace shell with bash so we can source files
#RUN rm /bin/sh && ln -s /bin/bash /bin/sh
#TODO 100

COPY ./init-project.sh /init-project.sh

RUN apk add --no-cache wget

# install sauce connect
# https://wiki.saucelabs.com/display/DOCS/Basic+Sauce+Connect+Proxy+Setup
RUN wget --no-check-certificate https://saucelabs.com/downloads/sc-4.4.8-linux.tar.gz
RUN tar -xf sc-4.4.8-linux.tar.gz
