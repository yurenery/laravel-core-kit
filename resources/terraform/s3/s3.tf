output "dev_resources_bucket_uri" {
  value = aws_s3_bucket.dev-resources.bucket
}

output "dev_resources_bucket_url" {
  value = aws_s3_bucket.dev-resources.bucket_regional_domain_name
}

output "prod_resources_bucket_uri" {
  value = aws_s3_bucket.prod-resources.bucket
}

output "prod_resources_bucket_url" {
  value = aws_s3_bucket.prod-resources.bucket_regional_domain_name
}

output "stage_resources_bucket_uri" {
  value = aws_s3_bucket.stage-resources.bucket
}

output "stage_resources_bucket_url" {
  value = aws_s3_bucket.stage-resources.bucket_regional_domain_name
}

resource "aws_s3_bucket" "dev-resources" {
  bucket = local.s3.devResources
  acl    = "private"

  tags = {
    Name       = local.s3.devResources
    Maintainer = local.tags.maintainer
    Developer  = local.tags.developer
  }

  cors_rule {
    allowed_methods = [
      "GET",
      "PUT"
    ]
    allowed_origins = [
      "*"
    ]
    allowed_headers = [
      "*"
    ]
    expose_headers  = [
      "ETag"
    ]
    max_age_seconds = 3000
  }

  lifecycle_rule {
    enabled = false
    id      = "tmp"
    prefix  = "tmp/"

    expiration {
      days = 1
    }
  }
}

resource "aws_s3_bucket" "stage-resources" {
  bucket = local.s3.stageResources
  acl    = "private"

  tags = {
    Name       = local.s3.stageResources
    Maintainer = local.tags.maintainer
    Developer  = local.tags.developer
  }

  cors_rule {
    allowed_methods = [
      "GET",
      "PUT"
    ]
    allowed_origins = [
      "*"
    ]
    allowed_headers = [
      "*"
    ]
    expose_headers  = [
      "ETag"
    ]
    max_age_seconds = 3000
  }

  lifecycle_rule {
    enabled = false
    id      = "tmp"
    prefix  = "tmp/"

    expiration {
      days = 1
    }
  }
}

resource "aws_s3_bucket" "prod-resources" {
  bucket = local.s3.prodResources
  acl    = "private"

  tags = {
    Name       = local.s3.prodResources
    Maintainer = local.tags.maintainer
    Developer  = local.tags.developer
  }

  cors_rule {
    allowed_methods = [
      "GET",
      "PUT"
    ]
    allowed_origins = [
      "*"
    ]
    allowed_headers = [
      "*"
    ]
    expose_headers  = [
      "ETag"
    ]
    max_age_seconds = 3000
  }

  lifecycle_rule {
    enabled = false
    id      = "tmp"
    prefix  = "tmp/"

    expiration {
      days = 1
    }
  }
}

resource "aws_s3_bucket_public_access_block" "dev-resources" {
  bucket = aws_s3_bucket.dev-resources.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_public_access_block" "stage-resources" {
  bucket = aws_s3_bucket.stage-resources.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_public_access_block" "prod-resources" {
  bucket = aws_s3_bucket.prod-resources.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}