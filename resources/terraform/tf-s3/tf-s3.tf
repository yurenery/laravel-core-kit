data "aws_canonical_user_id" "current_user" {}

output "tf_s3_uri" {
  value = aws_s3_bucket.tf-backend.bucket
}

output "tf_s3_url" {
  value = aws_s3_bucket.tf-backend.bucket_regional_domain_name
}

resource "aws_s3_bucket" "tf-backend" {
  bucket = local.s3.tfBucket

  tags = {
    Name       = local.s3.tfBucket
    Maintainer = local.tags.maintainer
    Developer  = local.tags.developer
  }

  grant {
    id          = data.aws_canonical_user_id.current_user.id
    type        = "CanonicalUser"
    permissions = [
      "READ",
      "WRITE" ]
  }
}

resource "aws_s3_bucket_public_access_block" "tf-backend" {
  bucket = aws_s3_bucket.tf-backend.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}