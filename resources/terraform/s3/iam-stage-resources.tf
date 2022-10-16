output "stage_resources_access_id" {
  value = aws_iam_access_key.stage-s3.id
}

output "stage_resources_access_secret" {
  value = aws_iam_access_key.stage-s3.encrypted_secret
}

resource "aws_iam_user" "stage-s3" {
  name = local.s3.iamStageResources
  path = "/s3/"

  tags = {
    Name       = local.s3.iamStageResources
    Maintainer = local.tags.maintainer
    Developer  = local.tags.developer
  }
}

resource "aws_iam_access_key" "stage-s3" {
  user    = aws_iam_user.stage-s3.name
  pgp_key = local.s3.pgpKeyValue
}

resource "aws_iam_user_policy" "stage-s3-policy" {
  name = "s3@stage@resources-access"
  user = aws_iam_user.stage-s3.name

  policy = <<EOF
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "VisualEditor0",
            "Effect": "Allow",
            "Action": "s3:*",
            "Resource": "${aws_s3_bucket.stage-resources.arn}/*"
        }
    ]
}
EOF
}