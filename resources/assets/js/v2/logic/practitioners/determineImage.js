export default function(image, type) {
    return image
        ? image
        : `https://d35oe889gdmcln.cloudfront.net/assets/images/default_${type}_image.png`;
}
