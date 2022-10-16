terraform {
  backend "s3" {
    bucket  = "tf-attract-starter"
    key     = "data/s3/terraform.tfstate"
    region  = "eu-west-2"
    profile = "starter"
  }
}