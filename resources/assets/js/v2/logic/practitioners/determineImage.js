export default function(image, type) {
    return image
        ? image
        : `https://s3.cloudfront.goharvey.com/assets/images/default_${type}_image.png`;
}
