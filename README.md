# photowalkboston
[photowalkboston.com](https://photowalkboston.com)

A website built for a local Boston creative photography meetup.

## Setup

- `docker compose up --build -d`
- `docker compose exec php bash`
- `composer install`
- `symfony serve --allow-all-ip -d`
- Go to [https://localhost:8081](https://localhost:8081)

## About

This repository serves two purposes:
1. To serve as a useful website for a meetup group I co-host
2. To help keep my skills sharp in a variety of technologies across the software stack and lifecycle.

Following is a list of technologies and practices used and honed with this repo.

### Frontend

The frontend is rendered server-side using the Twig templating engine.

It uses Bootstrap 5, for the grid layout and mobile-first responsive design.

The frontend is the least developed area of this repo.

### Backend

The backend is written in Symfony. I aim to keep it current with new releases and Flex recipes.

A SQLite database stores the site content, abstracted through Doctrine.

Admins use Sonata Admin to create site content.

### Cloud Infrastructure

#### Hosting

Rather than hosting on dedicated or shared servers, this website runs on a combination of AWS Lambda and S3 to minimize
costs.

A CloudFront distribution caches and serves static assets such as css and js files, while routing other requests to a
Lambda function via API Gateway. Such requests are handled by Symfony's front controller.

#### Database

Since the content database is small, I use SQLite instead of a more robust database technology. This saves greatly on
cost. Like any database, it must be persistent, readable, and writable. Since Lambdas are ephemeral and (mostly)
read-only, I mount an elastic file system containing the SQLite database file to each Lambda instantiation. This lets
the Lambda read and write a single persistent SQLite database at the mount point.

Something similar could have been achieved by storing the SQLite database in S3. However, I didn't go with that approach
because S3 can't be used as a file system at the time of writing. So, it would mean downloading the database at least
once per Lambda initialization, and replacing the file via upload every time there was a database change. That could lead
to overwritten work with multiple editors working at the same time.

#### VPC

A single VPC contains the Lambda and the vietual file system.

This VPC has a single subnet, which is public.

Lambdas in a VPC cannot by default to talk to the Internet (even in a public subnet), since they never have a public IP
address. This Lambda needs access to S3 for admins to manage uploads. Since S3 is available only over the Internet, the
Lambda can't talk to it by default. To solve the issue, I added a VPC Gateway Endpoint for S3 to the subnet, allowing
the Lambda to communicate with S3. I also added a VPC Interface endpoint for communication with SES, allowing the Lambda
to send emails.

A more common approach to networking would involve putting the Lambda in private subnets that connect to the Internet
through a NAT Gateway in a public subnet. I chose not to do use this approach to keep costs at a bare minimum, even
though it means the Lambda cannot originate requests to (most of) the Internet.

### CI/CD

#### Continuous Integration

CI and CD pipelines run in GitHub Actions.

CI pipelines are kicked off automatically when a PR is opened against `main`. Checks include linting, static analysis,
and unit/functional testing.

#### Continuous Deployment

The CD pipeline runs when `main` receives a push (for example, when a PR is merged).

This repo uses Serverless Framework to deploy the Lambda application and other cloud resources to AWS.

Additionally, deployments will update VPC and EFS resources for the application if any changes were made to their IaC,
which is stored as CloudFormation templates.

### Local development

A Dockerfile and docker-compose file allow developers to run this locally.

The image is based off of Bref images, which model what the inside of a Lambda looks like.
