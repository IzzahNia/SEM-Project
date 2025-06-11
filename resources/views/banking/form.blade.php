<x-app-layout>
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg border border-gray-200">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Online Banking Payment Form</h2>
            <p class="text-sm text-gray-500 mt-1">Please ensure all information is accurate before submitting.</p>
        </div>

        <form action="{{ route('banking.submit') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order_id }}">

            <!-- Bank Name -->
            <div>
                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                <select name="bank_name" id="bank_name" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Select Bank --</option>
                    <option value="Maybank">Maybank</option>
                    <option value="CIMB">CIMB</option>
                    <option value="RHB">RHB</option>
                    <option value="Public Bank">Public Bank</option>
                    <option value="Bank Islam">Bank Islam</option>
                    <option value="Hong Leong Bank">Hong Leong Bank</option>
                    <option value="Ambank">Ambank</option>
                    <option value="Bank Rakyat">Bank Rakyat</option>
                </select>
            </div>

            <!-- Account Holder -->
            <div>
                <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                <input type="text" name="account_holder" id="account_holder" placeholder="e.g., Ahmad bin Ali" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Reference Number -->
            <div>
                <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">Bank Account Number</label>
                <input type="text" name="reference_number" id="reference_number" placeholder="e.g., 1234567890" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Confirmation Checkbox -->
            <div class="flex items-start">
                <input type="checkbox" name="confirmation" id="confirmation" required class="mt-1 mr-2">
                <label for="confirmation" class="text-sm text-gray-700">
                    I confirm that I have completed the payment using the details provided above.
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <x-button class="w-full justify-center">Submit Payment</x-button>
            </div>
        </form>
    </div>
</x-app-layout>
