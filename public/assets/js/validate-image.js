// Function to validate file
function validateFile(file) {
    // Valid file types
    const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    
    // Check file type
    if (!validTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid File Type',
            text: 'Please upload PNG, JPEG, or JPG images only',
            confirmButtonText: 'Close'
        });
        return false;
    }
    
    // Check file size (10MB = 10 * 1024 * 1024 bytes)
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'File Too Large',
            text: 'Image size should not exceed 10MB',
            confirmButtonText: 'Close'
        });
        return false;
    }
    
    return true;
}