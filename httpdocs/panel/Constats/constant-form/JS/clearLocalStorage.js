// Only define if not already defined
if (typeof window.clearConstatStorage === 'undefined') {
    const clearConstatStorage = () => {
        const keysToDelete = [
            'damage-point-A',
            'damage-point-B',
            'util_count_a',
            'util_count_b'
        ];

        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('sc')) {
                localStorage.removeItem(key);
            }
            if (key.startsWith('meta-sc')) {
                localStorage.removeItem(key);
            }
            if (keysToDelete.includes(key)) {
                localStorage.removeItem(key);
            }
        });
    };

    window.clearConstatStorage = clearConstatStorage;
}