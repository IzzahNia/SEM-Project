<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\RedeemReward;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManageRewardController extends Controller
{
    public function showRewardList()
    {
        // Get the logged-in user
        $user = Auth::user();
    
        // Initialize default values
        $availableRewardsCount = 0;
        $userRewardPoints = $user->current_reward_points ?? 0;
    
        // Filter rewards based on user role
        $rewards = Reward::query()
            ->when($user->hasRole('user'), function ($query) {
                $query->where('reward_quantity', '>', 0) // Only rewards with available stock
                      ->where('reward_status', 'available'); // Only active rewards
            })
            ->get();
    
        // If the user is logged in and has a role that requires counting unclaimed rewards
        if ($user && $user->hasRole('user')) {
            $availableRewardsCount = RedeemReward::where('user_id', $user->id)
                ->where('redeem_code_status', 'Unclaimed')
                ->count();
        }
            $totalRedeemedRewardsCount = RedeemReward::all()->count();  
    
        return view('ManageReward.reward_list', compact('rewards', 'user', 'availableRewardsCount', 'userRewardPoints', 'totalRedeemedRewardsCount'));
    }    

    public function addReward()
    {
        return view('ManageReward.add_reward_form');
    }

    public function createReward(Request $request)
    {
        $request->validate([
            'reward_name' => 'required',
            'reward_description' => 'required',
            'reward_duration_date' => 'required',
            'reward_status' => 'required',
            'reward_quantity' => 'required',
            'reward_point_required' => 'required',
            'reward_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('reward_image')) {
            $imageName = time() . '.' . $request->reward_image->extension();
            $request->reward_image->move(public_path('images/rewards'), $imageName);
        }
    
        $rewards = Reward::create([
            'reward_name' => $request->reward_name,
            'reward_description' => $request->reward_description,
            'reward_duration_date' => $request->reward_duration_date,
            'reward_quantity' => $request->reward_quantity,
            'reward_point_required' => $request->reward_point_required,
            'reward_status' => $request->reward_status,
            'reward_image' => $imageName, // Save the image name in the database
        ]);
    
        return redirect()->route('reward.list');
    }

    public function editReward($id)
    {
        $reward = Reward::findOrFail($id);
        return view('ManageReward.edit_reward_form', compact('reward'));
    }

    public function updateReward(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'reward_name' => 'required',
            'reward_description' => 'required',
            'reward_duration_date' => 'required',
            'reward_status' => 'required',
            'reward_quantity' => 'required',
            'reward_point_required' => 'required',
            'reward_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $imageName = $reward->reward_image; // Initialize the image name variable
        // Check if a new image is being uploaded
        if ($request->hasFile('reward_image')) {
            // Delete the old image if it exists
            if ($imageName && file_exists(public_path('images/rewards/' . $imageName))) {
                unlink(public_path('images/rewards/' . $imageName));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->reward_image->extension();
            $request->reward_image->move(public_path('images/rewards'), $imageName);
        }

        // Update the reward
        $reward->update([
            'reward_name' => $request->reward_name,
            'reward_description' => $request->reward_description,
            'reward_duration_date' => $request->reward_duration_date,
            'reward_quantity' => $request->reward_quantity,
            'reward_point_required' => $request->reward_point_required,
            'reward_status' => $request->reward_status,
            'reward_image' => $imageName, // Save the image name in the database
        ]);

        // Redirect to the reward list with a success message
        return redirect()->route('reward.list')->with('success', 'Reward updated successfully!');
    }
    
    public function deleteReward($id) {
        $reward = Reward::find($id);

        // Check if the product exists and if it has an associated image
        if ($reward && $reward->reward_image) {
            // Define the file path
            $imagePath = public_path('images/rewards/' . $reward->reward_image);
            
            // Check if the file exists and delete it
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $reward->delete();
        return redirect()->route('reward.list')->with('success', 'Reward deleted successfully!');
    }

    public function viewReward($id)
    {
        $reward = Reward::findOrFail($id);
        return view('ManageReward.view_reward_form', compact('reward', 'id'));
    }

    public function redeemReward(Request $request, $id)
    {
        $currentUserId = Auth::id();
        $user = Auth::user();
        $userRewardPoints = $user->current_reward_points;
        $reward = Reward::findOrFail($id);

        // Check if the user has enough reward points
        if ($userRewardPoints >= $reward->reward_point_required) {
            // Decrement the user's reward points
            $user->decrement('current_reward_points', $reward->reward_point_required);
            // Decrement the reward quantity
            $reward->decrement('reward_quantity', 1);

            // Generate a 6-digit code for the redeemed item
            $redeemedCode = rand(100000, 999999);
            $code_expired_date = Carbon ::now()->addDays(7);

            // Save the redeemed reward information
            $redeemReward = new RedeemReward();
            $redeemReward->user_id = $currentUserId;
            $redeemReward->reward_id = $reward->id;
            $redeemReward->redeem_code = $redeemedCode;
            $redeemReward->code_expired_date = $code_expired_date;
            $redeemReward->redeem_code_status = 'Unclaimed';
            $redeemReward->save();

            // Return a success message or redirect to a success page
            return redirect()->route('reward.list');
        } else {
            // Return an error message or redirect to an error page
            return redirect()->route('reward.list');
        }
        
    }

    
    public function rewardRedemption()
    {
        return view('ManageReward.code_redemption_form');
    }

    public function redeemRewardCode(Request $request)
    {
        $request->validate([
            'redeem_code' => 'required|string|exists:redeem_rewards,redeem_code',
        ]);
    
        // Find the RedeemReward by its redeem code
        $redeemReward = RedeemReward::where('redeem_code', $request->input('redeem_code'))->first();
    
        if (!$redeemReward) {
            // The code is invalid
            return response()->json(['error' => 'The redemption code is invalid.'], 422);
        }
    
        // Check if the code is expired
        if ($redeemReward->code_expired_date < now()) {
            // The code is expired
            return response()->json(['error' => 'The redemption code has expired.'], 422);
        }

        // Update the redeem_code_status to 'Redeemed'
        $redeemReward->redeem_code_status = 'Redeemed';
        $redeemReward->code_redeemed_date = now();
        $redeemReward->save();

        $reward = Reward::find($redeemReward->reward_id);

        if (!$reward) {
            return response()->json(['error' => 'Reward not found.'], 404);
        }

        // Redirect to success page with the required information
        return redirect()->route('redeem.code.success', [
            'reward_id' => $reward->id, // Use the reward's ID
            'user_id' => $redeemReward->user_id, // Pass the user ID
        ]);
    }

    public function redeemCodeSuccess(Request $request)
    {      
        $reward_id = $request->input('reward_id');
        $user_id = $request->input('user_id');

        $reward = Reward::find($reward_id);
        $user = User::find($user_id);
        
        return view('ManageReward.code_redemption_success', [
            'reward_id' => $request->input('reward_id'),
            'reward_name' => $reward->reward_name,
            'reward_description' => $reward->reward_description,
            'reward_image' => $reward->reward_image,
            'user_id' => $request->input('user_id'), // Retrieve the user ID
            'user_name' => $user->name,
        ]);
    }

    public function showMyRewardList()
    {
        $currentUserId = Auth::id();
        $user = Auth::user();

        if ($user->role === 'admin') {
            $redeemRewards = RedeemReward::all();
        } else {
            $redeemRewards = RedeemReward::where('user_id', $user->id)->get();
        }

        // Get the user's current reward points
        $userRewardPoints = $user->current_reward_points;
        
        if ($user->role === 'admin') {
            $availableRewardsCount = RedeemReward::all()
                                            ->where('redeem_code_status', 'Unclaimed')
                                            ->count();

            $totalRewardsCount = RedeemReward::all()
                                            ->count();  
        } else {
            // Count the rewards with status 'Available' for the logged-in user
            $availableRewardsCount = RedeemReward::where('user_id', $user->id)
                                            ->where('redeem_code_status', 'Unclaimed')
                                            ->count();

            $totalRewardsCount = RedeemReward::where('user_id', $user->id)
                                            ->count();                                
        }

        return view('ManageReward.my_reward_list', compact('redeemRewards', 'availableRewardsCount', 'totalRewardsCount'));
    }

    public function viewMyReward($id)
    {
        $redeemReward = RedeemReward::findOrFail($id);

        return view('ManageReward.view_redeemed_reward_form', compact('redeemReward'));
    }
}
